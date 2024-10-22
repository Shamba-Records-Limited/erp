<?php

namespace App\Http\Controllers;

use App\BulkPayment;
use App\CollectionQualityStandard;
use App\Exceptions\FailedToCompleteFarmerPaymentException;
use App\Exports\BulkPaymentExport;
use App\Exports\BulkPaymentFarmers;
use App\Exports\CollectionByProductExport;
use App\Exports\CollectionExport;
use App\Exports\FarmerCollectionExport;
use App\Exports\FarmerPaymentExport;
use App\Exports\ProcessedBulkPaymentExport;
use App\Exports\SubmittedCollectionExport;
use App\Http\Traits\WalletTrait;
use App\Wallet;
use App\WalletTransaction;
use Cache;
use Illuminate\Http\Request;
use App\Collection;
use App\Product;
use App\Farmer;
use App\Events\AuditTrailEvent;
use Illuminate\Support\Facades\Auth;
use DB;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;
use Log;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception;
use Throwable;

class CollectionController extends Controller
{

    use WalletTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    //return view
    public function index(Request $request)
    {
        $coop = Auth::user()->cooperative->id;
        $collections = Collection::get_collections($coop, $request, 100);
        $recent_collections = Collection::where('cooperative_id', $coop)->latest()->limit(10)->get();
        $products = Product::with(['unit'])->orderBy('name')->get();
        $agents = get_agents($coop);
        return view('pages.cooperative.collections.index', compact(
            'collections',
            'products',
            'recent_collections',
            'agents'
        ));
    }

    public function collectionsByProduct(Request $request, $productId)
    {

        $collections = Collection::get_collections_by_product($request, $productId, 100);
        $coop = Auth::user()->cooperative->id;
        $farmers = get_cooperative_farmers($coop);
        $quality_stds = CollectionQualityStandard::getStandardQualities($coop);
        $agents = get_agents($coop);

        return view('pages.cooperative.collections.details',
            compact('collections', 'farmers', 'quality_stds', 'agents', 'productId'));

    }

    public function getFarmers()
    {
        $coop = Auth::user()->cooperative->id;
        return get_cooperative_farmers($coop);
    }

    public function getAgents()
    {
        $coop = Auth::user()->cooperative->id;

        return get_agents($coop);

    }

    public function getStandardQualities()
    {
        $coop = Auth::user()->cooperative->id;
        return CollectionQualityStandard::getStandardQualities($coop);
    }


    //store
    public function store(Request $request)
    {

        $request->validate(
            [
                'farmer_id' => 'required',
                'product' => 'required',
                'quantity' => 'required|numeric',
                'standard_id' => 'required',
                'collection_time' => 'required'
            ],
            [
                'farmer_id.required' => 'Farmer required'
            ]
        );


        try {
            DB::beginTransaction();
            //create collection
            $user = Auth::user();
            $coop = $user->cooperative_id;

            $product = Product::find($request->product['id']);

            $collection = new Collection();
            $req = [
                "farmerId" => $request->farmer,
                "productId" => $request->product['id'],
                "availableQuantity" => $request->quantity,
                "batchNo" => strtoupper('C' . Carbon::now()->format('Ymd')),
                "agentId" => $user->id,
                "cooperative" => $user->cooperative_id,
                "quality" => $request->standard_id['id'],
                "comments" => $request->comments,
                "collection_time" => $request->collection_time['key'],
                "submission_status" => Collection::SUBMISSION_STATUS_APPROVED,
                "unit_price" => $product->buying_price,
            ];

            $loadWalletRequest = [
                "productId" => $request->product['id'],
                'farmerId' => $request->farmer,
                'quantity' => $request->quantity,
            ];

            $collection = $collection->saveCollection($req);
            $audit_trail_data = [
                'user_id' => $user->id,
                'activity' => 'Added collection of product #' . $collection->product_id . ' from farmer #' . $collection->farmer_id,
                'cooperative_id' => $coop
            ];
            event(new AuditTrailEvent($audit_trail_data));
            if ($this->load_wallet($loadWalletRequest)) {
                DB::commit();
                toastr()->success('Collection recorded successfully');
                return response()->json('Success');
            } else {
                DB::rollBack();
                Log::error("Failed to load wallet");
                toastr()->error('Oops! Failed to create collection');
                return response()->json('Failed to load wallet', 500);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            toastr()->error('Oops! Failed to create collection');
            return response()->json('Error' . $e->getMessage(), 500);
        }
    }

    //save to wallet
    private function load_wallet($request): bool
    {
        $user = Auth::user();
        $productId = $request['productId'];
        $farmerId = $request['farmerId'];
        $quantity = $request['quantity'];
        try {
            DB::beginTransaction();
            //get product price
            $product = Product::find($productId);
            $wallet = Wallet::where('farmer_id', $farmerId)->first();
            if ($wallet) {
                //update current balance
                $wallet->current_balance += ($product->buying_price * $quantity);
                $wallet->save();
            } else {
                //create farmer wallet
                $balance = $product->buying_price * $quantity;
                $wallet = default_wallet($farmerId, $balance);
            }

            $wallet_transaction = new WalletTransaction();
            $wallet_transaction->wallet_id = $wallet->id;
            $wallet_transaction->type = 'collection';
            $wallet_transaction->amount = $product->buying_price * $quantity;
            $wallet_transaction->reference = 'COL' . date('Ymdhis');
            $wallet_transaction->source = 'internal';
            $wallet_transaction->initiator_id = $user->id;
            $wallet_transaction->description = 'Transaction from submitted collection';
            $wallet_transaction->phone = null;
            $wallet_transaction->save();

            //increase debt and increase asset
            $farmer_debt_amount = $product->buying_price * $quantity;
            $farmer = Farmer::find($farmerId)->user;
            $farmer_names = ucwords(strtolower($farmer->first_name . ' ' . $farmer->other_names));

            if (
                create_account_transaction('Farmer Collections', $farmer_debt_amount, 'Credit purchase of farm produce from farmer: ' . $farmer_names)
            ) {
                $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Wallet current balance updated with ' . $product->buying_price * $quantity . ' amount from collection', 'cooperative_id' => $user->cooperative->id];
                event(new AuditTrailEvent($audit_trail_data));
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);
            $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Failed to update current balance with ' . $product->buying_price * $request->quantity . ' amount from collection', 'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            return false;
        }
    }

    public function submittedCollections(Request $request)
    {
        $coop = Auth::user()->cooperative_id;
        $collections = Collection::submitted_collections($coop, $request, 100);
        $farmers = get_cooperative_farmers($coop);
        $quality_stds = CollectionQualityStandard::getStandardQualities($coop);
        $products = Product::select(['name', 'id'])->where('cooperative_id', $coop)
            ->orderBy('name')
            ->get();
        return view('pages.cooperative.collections.submitted_collections',
            compact('collections', 'farmers', 'quality_stds', 'products'));
    }

    public function updateSubmissionStatus(Request $request, $collectionId): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'status' => 'required'
        ]);
        try {
            DB::beginTransaction();
            $collection = Collection::findOrFail($collectionId);
            $collection->submission_status = $request->status;
            $collection->save();

            if ($request->status == Collection::SUBMISSION_STATUS_APPROVED) {
                $loadWalletRequest = [
                    "productId" => $collection->product_id,
                    'farmerId' => $collection->farmer_id,
                    'quantity' => $collection->quantity,
                ];

                if ($this->load_wallet($loadWalletRequest)) {
                    DB::commit();
                    toastr()->success('Collection recorded successfully');
                    return redirect()->back();
                } else {
                    DB::rollBack();
                    Log::error("Failed to load wallet");
                    toastr()->error('Oops! Failed to create collection');
                    return redirect()->back();
                }
            } else {
                DB::commit();
                toastr()->success('Collection Status updated!');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back();
        }
    }

    //edit
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'product' => 'required',
                'quantity' => 'required',
                'date' => 'required'
            ],
            [
                'farmer_id.required' => 'Farmer ID No required'
            ]
        );
        try {
            //create collection
            $user = Auth::user();
            $coop = $user->cooperative->id;
            $collection = Collection::find($id);
            $collection->quantity = $request->quantity;
            $collection->date_collected = Carbon::create($request->date);
            $collection->comments = $request->comments;

            //wallet update

            if ($collection['available_quantity'] != $request->quantity) {

                $loadWalletRequest = [
                    "productId" => $collection->product_id,
                    'farmerId' => $collection->farmer_id,
                    'quantity' => ($request->quantity - $collection->available_quantity)
                ];

                $collection->available_quantity = $request->quantity;
                $collection->quantity = $request->quantity;
                if (!$this->load_wallet($loadWalletRequest)) {
                    DB::rollBack();
                    Log::error("Failed to update wallet after collections update");
                    toastr()->error('Failed to update wallet balance');
                    return redirect()->back()->withInput();
                }
            }

            $collection->save();
            $audit_trail_data = ['user_id' => $user->id,
                'activity' => 'Update collection of product #'
                    . $collection->product_id . ' of farmer #'
                    . $collection['farmer_id'],
                'cooperative_id' => $coop
            ];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Collection updated successfully');
            DB::commit();
            return back();
        } catch (\Exception $e) {
            Log::info($e);
            toastr()->error('Oops! Failed to update collection');
            return back();
        }
    }

    ///get report data
    public function getReports(Request $request)
    {
        $coop = Auth::user()->cooperative->id;

        if ($request->date) {
            $dates = split_dates($request->date);
            $from = $dates['from'];
            $to = $dates['to'];

            $diff_in_days = Carbon::parse($from)->diffInDays(Carbon::parse($to));

            $old_from = Carbon::parse($dates['from'])->subDays($diff_in_days)->format('Y-m-d');
            $old_to = $dates['from'];

        } else {
            $to = Carbon::now()->format('Y-m-d');
            $from = Carbon::now()->subWeek()->format('Y-m-d');

            $old_to = Carbon::now()->subWeek()->format('Y-m-d');
            $old_from = Carbon::now()->subWeeks(2)->format('Y-m-d');
        }

        $latest_collections = Collection::where('cooperative_id', $coop)
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)
            ->whereBetween('date_collected', [$from, $to])->latest()->take(5)->get();

        $volume_collected = Collection::where('cooperative_id', $coop)->with(['farmer', 'product', 'agent'])
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)
            ->whereBetween('date_collected', [$from, $to])->sum('quantity');

        $volume_collected_old = Collection::where('cooperative_id', $coop)->with(['farmer', 'product', 'agent'])
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)
            ->whereBetween('date_collected', [$old_from, $old_to])->sum('quantity');

        $collection_products = Collection::where('cooperative_id', $coop)->with(['product'])
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)
            ->whereBetween('date_collected', [$from, $to])->latest()->count();

        $old_collection_products = Collection::where('cooperative_id', $coop)->with(['product'])
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)
            ->whereBetween('date_collected', [$old_from, $old_to])->latest()->count();

        $collection_farmers = Collection::where('cooperative_id', $coop)
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)->with(['farmer'])
            ->whereBetween('date_collected', [$from, $to])->latest()->count();
        $old_collection_farmers = Collection::where('cooperative_id', $coop)
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)->with(['farmer'])
            ->whereBetween('date_collected', [$old_from, $old_to])->latest()->count();
        $data = [
            'latest' => $latest_collections,
            'volume_collected' => $volume_collected,
            'volume_collected_old' => $volume_collected_old,
            'products' => $collection_products,
            'old_products' => $old_collection_products,
            'farmers' => $collection_farmers,
            'old_farmers' => $old_collection_farmers,
        ];

        return view('pages.cooperative.collections.report', compact('data'));
    }

    public function get_dashboard_stats(Request $request): array
    {
        $cooperative = Auth::user()->cooperative->id;
        return [
            "collections" => $this->get_weekly_product_collection($cooperative, $request),
        ];
    }

    private function get_weekly_product_collection($cooperative, $request)
    {
        if ($request->date) {
            $dates = split_dates($request->date);
            $from = $dates['from'];
            $to = $dates['to'];
        } else {
            $from = Carbon::now()->subWeek()->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');
        }
        $submission_status = Collection::SUBMISSION_STATUS_APPROVED;
        $collections_data = DB::select("
        SELECT p.name AS product, SUM(col.quantity) AS quantity, u.name as unit
        FROM collections col JOIN products p ON col.product_id = p.id
        JOIN units u on p.unit_id = u.id
        WHERE col.cooperative_id = '$cooperative' 
        AND col.submission_status = '$submission_status'
        AND col.date_collected BETWEEN  '$from' and '$to'
        GROUP BY product, unit
        ORDER BY product ASC;
        ");


        $collections = [];

        foreach ($collections_data as $col) {
            $collections[] = (object)[
                "product" => $col->product . "($col->unit)",
                "quantity" => $col->quantity
            ];
        }

        return $collections;
    }


    public function export_collection($type, Request $request)
    {
        $cooperative = Auth::user()->cooperative->id;
        if ($request->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($request->request_data);
        }

        $collections = Collection::get_collections($cooperative, $request, null);
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('collections_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new CollectionExport($collections), $file_name);
        } else {
            $data = [
                'title' => 'Collections By Product',
                'pdf_view' => 'collections',
                'records' => $collections,
                'filename' => strtolower('collections_' . date('d_m_Y')),
                'orientation' => 'portrait'
            ];
            return download_pdf($data);
        }
    }


    public function export_collection_by_product($productId, $type, Request $request)
    {
        if ($request->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($request->request_data);
        }

        $product = ucwords(Product::findOrFail($productId)->name);
        $file_name = strtolower(str_replace(' ', '_', $product) . '_collections_' . date('d_m_Y'));

        $collections = Collection::get_collections_by_product($request, $productId, null);
        if ($type != env('PDF_FORMAT')) {
            $file_name .= '.' . $type;
            return Excel::download(new CollectionByProductExport($collections), $file_name);
        } else {
            $data = [
                'title' => $product . ' Collections',
                'pdf_view' => 'product_collections',
                'records' => $collections,
                'filename' => $file_name,
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function export_farmer_collection(Request $request, $type, $farmer_id)
    {
        $farmer = Farmer::find($farmer_id);
        $collections = Collection::farmer_collections($farmer->user->cooperative_id, $farmer->id);
        $file_name = 'collection_history_'
            . $farmer->user->first_name . '_'
            . $farmer->user->other_names . '_'
            . date('d_m_Y');
        if ($type != env('PDF_FORMAT')) {
            $file_name .= '.' . $type;
            return Excel::download(new FarmerCollectionExport($collections), $file_name);
        }else{

            $names = ucwords(strtolower($farmer->user->first_name.' '.$farmer->user->other_names));
            $data = [
                'title' => "{$names} Collection History",
                'pdf_view' => 'farmer_collection_history',
                'records' => $collections,
                'filename' => $file_name,
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function view_farmer_collection($farmer_id)
    {
        $from = request()->from;
        $to = request()->to;
        $cooperative = Auth::user()->cooperative->id;
        $query = Collection::where("cooperative_id", $cooperative)
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED);

        if ($from) {
            Cache::put('farmer_collection_from', $from, now()->addMinutes(5));
            $from = Carbon::parse($from)->format('Y-m-d');
            $query = $query->whereDate('date_collected', '>=', $from);
        }

        if ($to) {
            Cache::put('farmer_collection_to', $to, now()->addMinutes(5));
            $to = Carbon::parse($to)->format('Y-m-d');
            $query = $query->whereDate('date_collected', '<=', $to);
        }

        $farmer_collections = $query->where('farmer_id', $farmer_id)->get();

        return view('pages.farmer.collections', compact('farmer_collections', 'farmer_id'));
    }

    public function export_submitted_collections($type, Request $request)
    {
        $cooperative = Auth::user()->cooperative->id;
        if ($request->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($request->request_data);
        }

        $file_name = 'submitted_collections_' . date('d_m_Y');

        $collections = Collection::submitted_collections($cooperative, $request, null);
        if ($type != env('PDF_FORMAT')) {
            $file_name .= '.' . $type;
            return Excel::download(new SubmittedCollectionExport($collections), $file_name);
        } else {
            $data = [
                'title' => ' Submitted Collections',
                'pdf_view' => 'submitted_collections',
                'records' => $collections,
                'filename' => $file_name,
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function downloadReceipt($id)
    {

        $user = Auth::user();
        $collection = Collection::findOrFail($id);

        $client = new Party([
            'name' => $user->cooperative->name,
            'phone' => $user->cooperative->contact_details,
            'email' => $user->cooperative->email,
            'custom_fields' => [
                'address' => $user->cooperative->address,
                'Generated By' => ucwords(strtolower($user->first_name . ' ' . $user->other_names)),
            ],
        ]);


        $customer = new Buyer([
            'name' => ucwords(strtolower(
                $collection->farmer->user->first_name . ' ' . $collection->farmer->user->other_names)),
            'custom_fields' => [
                'email' => $collection->farmer->user->email,
                'address' => $collection->farmer->route->name,
            ],
        ]);

        $items = [(new InvoiceItem())
            ->title($collection->product->name)
            ->description($collection->product->unit->name)
            ->quantity($collection->quantity)
            ->pricePerUnit($collection->product->buying_price)
            ->subTotalPrice($collection->quantity * $collection->product->buying_price)
            ->discount(0)];

        $invoice_name = "Collection Acknowledgment Receipt";
        $currency = $user->cooperative->currency ?? 'KES';
        $collection_time = config('enums.collection_time')[$collection->collection_time];
        $collection_date = Carbon::parse($collection->date_collected)->format('Y-m-d, l');
        $notes = "Collections was submitted on {$collection_date} {$collection_time}";
        $invoice = Invoice::make()
            ->name($invoice_name)
            ->status(__(''))
            ->seller($client)
            ->serialNumberFormat($collection->collection_number)
            ->buyer($customer)
            ->currencySymbol($currency)
            ->currencyThousandsSeparator(',')
            ->addItems($items)
            ->logo(public_path($user->cooperative->logo ?? 'assets/images/favicon.png'))
            ->notes($notes)
            ->template('collection_receipt');

        return $invoice->stream();
    }

    public function bulk_payment(Request $request)
    {
        $user = Auth::user();
        $coop = $user->cooperative_id;
        $products = Product::select(['name', 'id'])->where('cooperative_id', $coop)
            ->orderBy('name')
            ->get();
        $pending_payments = Collection::pending_payments($coop, $request);
        $bulk_payments = BulkPayment::bulk_payment_batches($coop, $request, 100);
        if($request->product){
            $filtered_product = Product::findOrFail($request->product);
        }else{
            $filtered_product = null;
        }
        return view('pages.cooperative.collections.bulk-payments', compact('pending_payments', 'products', 'bulk_payments', 'filtered_product'));
    }

    public function bulk_payment_pay(Request $r)
    {
        $user = Auth::user();
        $mode = $r->mode;
        $cooperative = $user->cooperative_id;
        if ($r->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($r->request_data);
        }

        if($r->product_id){
            $p = Product::findOrFail($r->product_id);
            $p->buying_price = $r->product_unit_price;
            $p->save();
        }

        $pending_payments = Collection::pending_payments($cooperative, $request);

        try {
            DB::beginTransaction();
            $total_amount = 0;
            $batch = 'BPAY' . Carbon::now()->format('Ymd') . strtoupper(substr(generate_password(), 2, 2));
            foreach ($pending_payments as $payment_request) {
                if ($payment_request->pending_payments > 0) {
                    $amount = $payment_request->collection_worth < $payment_request->pending_payments ?
                        $payment_request->collection_worth : $payment_request->pending_payments;
                    $r = (object)[
                        "farmer_id" => $payment_request->farmer_id,
                        "amount" => $amount
                    ];

                    $total_amount += $amount;
                    $this->pay_farmer_util($r, 'Bulk Payment Transaction', $batch . '-', $mode);
                }
            }

            $bulPayment = new BulkPayment();
            $bulPayment->batch = $batch;
            $bulPayment->total_amount = $total_amount;
            $bulPayment->created_by_id = $user->id;
            $bulPayment->cooperative_id = $user->cooperative->id;
            $bulPayment->mode = $mode;
            $bulPayment->status = $mode == BulkPayment::PAYMENT_MODE_INTERNAL_TRANSFER ?
                BulkPayment::PAYMENT_MODE_STATUS_COMPLETED : BulkPayment::PAYMENT_MODE_STATUS_PENDING;
            $bulPayment->updated_at =
                $mode == BulkPayment::PAYMENT_MODE_INTERNAL_TRANSFER ? Carbon::now() : null;
            $bulPayment->save();
            DB::commit();
            toastr()->success('Payments Completed');
            return redirect()->back()->withInput();
        } catch (Exception|FailedToCompleteFarmerPaymentException|Throwable $ex) {
            $user = Auth::user();
            DB::rollBack();
            Log::error($ex->getMessage());
            Log::error("---------------------------------");
            $audit_trail_data = ['user_id' => $user->id,
                'activity' => 'Failed to complete bulk payments [' . $ex->getMessage() . ']',
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->error('Oops! Failed to complete transactions');
            return redirect()->back()->withInput();
        }

    }

    public function export_bulk_payments($type, Request $request)
    {
        $cooperative = Auth::user()->cooperative->id;
        if ($request->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($request->request_data);
        }

        $file_name = 'pending_payments' . date('d_m_Y');

        $pending_payments = Collection::pending_payments($cooperative, $request);
        if ($type != env('PDF_FORMAT')) {
            $file_name .= '.' . $type;
            return Excel::download(new BulkPaymentExport($pending_payments), $file_name);
        } else {
            $data = [
                'title' => ' Pending  Payments',
                'pdf_view' => 'pending_payments',
                'records' => $pending_payments,
                'filename' => $file_name,
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function export_processed_bulk_payments($type, Request $request)
    {
        $cooperative = Auth::user()->cooperative->id;
        if ($request->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($request->request_data);
        }

        $file_name = 'processed_bulk_payments' . date('d_m_Y');

        $pending_payments = BulkPayment::bulk_payment_batches($cooperative, $request, null);
        if ($type != env('PDF_FORMAT')) {
            $file_name .= '.' . $type;
            return Excel::download(new ProcessedBulkPaymentExport($pending_payments), $file_name);
        } else {
            $data = [
                'title' => 'Processed Bulk Payments',
                'pdf_view' => 'processed_bulk_payments',
                'records' => $pending_payments,
                'filename' => $file_name,
                'orientation' => 'portrait'
            ];
            return download_pdf($data);
        }
    }

    public function complete_bulk_payments($id)
    {
        $bulkPayment = BulkPayment::findOrFail($id);

        $user = Auth::user();
        $bulkPayment->updated_at = Carbon::now();
        $bulkPayment->status = BulkPayment::PAYMENT_MODE_STATUS_COMPLETED;
        $bulkPayment->save();
        $audit_trail_data = ['user_id' => $user->id,
            'activity' => 'Complete Bulk Payment # ' . $bulkPayment->batch,
            'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Completed Successfully');
        return redirect()->back();
    }

    public function bulk_payment_farmers($id)
    {

        $bulkPayment = BulkPayment::findOrFail($id);
        $batch = $bulkPayment->batch;
        $cooperative = $bulkPayment->cooperative_id;

        $bulk_payment_farmers = BulkPayment::bulk_payments_farmers($cooperative, $batch);

        return view('pages.cooperative.collections.bulk-payment-farmers', compact('bulk_payment_farmers', 'batch'));
    }

    public function export_bulk_payment_farmers($batch, $type)
    {
        $bulkPayment = BulkPayment::where('batch', $batch)->first();
        $file_name = 'bulk_payments_farmers' . date('d_m_Y');

        $payments = BulkPayment::bulk_payments_farmers($bulkPayment->cooperative_id, $batch);
        if ($type != env('PDF_FORMAT')) {
            $file_name .= '.' . $type;
            return Excel::download(new BulkPaymentFarmers($payments), $file_name);
        } else {
            $data = [
                'title' => 'Bulk Payments #' . $batch . ' Farmers',
                'pdf_view' => 'bulk_payment_farmers',
                'records' => $payments,
                'filename' => $file_name,
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }
}
