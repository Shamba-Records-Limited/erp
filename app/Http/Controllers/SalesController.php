<?php

namespace App\Http\Controllers;

use App\Collection;
use App\Exports\SalesExport;
use App\Exports\SalesQuotationExport;
use App\Exports\SalesReturnExport;
use App\Http\Traits\Payment;
use App\ReturnedItem;
use App\Wallet;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use App\Invoice as Inv;
use App\InvoicePayment;
use App\Product;
use App\Production;
use App\Sale;
use App\SaleItem;
use App\User;
use App\Customer;
use App\Events\AuditTrailEvent;

use Illuminate\Support\Facades\Auth;
use Log;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Mail;

class SalesController extends Controller
{
    use Payment;

    //sales pos
    public function index(Request $request)
    {
        //get manufactured products
        $coop = Auth::user()->cooperative->id;
        //get customers
        $customers = Customer::latest()->get();
        //get farmers
        $farmers = User::where('cooperative_id', $coop)->whereHas('farmer')->with(['farmer'])->get();
        //get sale

        $sales = sales_base_query($coop, $request, 'sale')->latest()->limit(100)->get();

        return view('pages.cooperative.sales.pos.index', compact( 'sales', 'farmers', 'customers'));
    }

    public function voidedInvoices(Request $request)
    {
        $coop = Auth::user()->cooperative->id;
        $request["void"] = 1;
        //get customers
        $customers = Customer::latest()->get();
        //get farmers
        $farmers = User::where('cooperative_id', $coop)->whereHas('farmer')->with(['farmer'])->get();
        //get sales
        $sales = sales_base_query($coop, $request, 'sale', true)->latest()->limit(100)->get();
        return view('pages.cooperative.sales.pos.void-invoices', compact('sales', 'farmers', 'customers'));
    }

    public function quotationView(Request $request)
    {
        //get manufactured products
        $coop = Auth::user()->cooperative->id;
        //get customers
        $customers = Customer::latest()->get();
        //get farmers
        $farmers = User::where('cooperative_id', $coop)->whereHas('farmer')->with(['farmer'])->get();
        //get sales
        $sales = quotation_base_query($coop, $request)->latest()->limit(100)->get();

        return view('pages.cooperative.sales.pos.quote', compact( 'sales', 'farmers', 'customers'));
    }

    public function items($sale_id)
    {
        //get manufactured products
        $coop = Auth::user()->cooperative->id;
        $productions = Production::where('cooperative_id', $coop)
            ->where('available_quantity', '>', 0)
            ->latest()
            ->get();
        //get collected product
        $collections = Product::where('cooperative_id', $coop)->whereHas('collections', function ($query) {
            $query->where('available_quantity', '>', '0');
        })->with('collections')->latest()->get();
        //get sales
        $sale = Sale::where('id', $sale_id)->latest()->first();
        $items = SaleItem::where('sales_id', $sale_id)->orderBy('created_at', 'DESC')->limit(100)->get();
        return view('pages.cooperative.sales.pos.items', compact('sale', 'collections', 'productions', 'items', 'sale_id'));
    }

    //payments
    public function payments($sale_id)
    {
        $invoice = Inv::where('sale_id', $sale_id)->pluck('id');
        $invoice_id = $invoice[0];
        $payments = InvoicePayment::where('status', InvoicePayment::PAYMENT_STATUS_SUCCESS)
            ->whereHas('invoice', function ($query) use ($sale_id) {
                $query->where('sale_id', $sale_id);
            })->orderBy('created_at', 'DESC')->get();
        //get sales
        $sale = Sale::findOrFail($sale_id);
        if ($sale->farmer_id) {
            $wallet = Wallet::where('farmer_id', $sale->farmer_id)->first();
        } else {
            $wallet = null;
        }
        return view('pages.cooperative.sales.pos.payments', compact('sale', 'payments', 'sale_id', 'invoice_id', 'wallet'));
    }

    //pay
    public function pay(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:0',
            'mode' => 'required',
            'reference' => 'sometimes|nullable|required_unless:mode,' . InvoicePayment::PAYMENT_MODE_MPESA_STK_PUSH,
            'phone' => 'sometimes|nullable|required_if:mode,==,' . InvoicePayment::PAYMENT_MODE_MPESA_STK_PUSH . '|regex:/^[0-9]{10}$/'
        ], [
            'reference.required_unless' => 'Reference Code is required if Payment mode is not MPESA STK Push',
            'phone.required_unless' => 'Phone number is required if Payment mode is MPESA STK Push',
            'phone.regex' => 'Phone number format is invalid, please enter a Safaricom number in the format 07XXXXXXXX'
        ]);
        $user = Auth::user();

        try {
            //get sale
            $sale = Sale::findOrFail($request->sale_id);

            if ($request->amount > $sale->balance) {
                toastr()->warning('Your remaining balance is ' . $sale->balance . '. Pay less or exact amount');
                return redirect()->back()->withInput();
            }

            DB::beginTransaction();
            $payment = new InvoicePayment();
            $payment->invoice_id = $request->invoice_id;
            $payment->payment_platform = $request->mode;
            $payment->transaction_number = $request->reference ?? 'PENDING_MPESA_REF_ON' . date('Ymd-m-Y-H-i-s');
            $payment->instructions = $request->instructions;
            $payment->amount = $request->amount;
            $payment->initiator = $user->id;
            //update payment status
            $sale->save();
            $payment->status = InvoicePayment::PAYMENT_STATUS_IN_PROGRESS;
            $payment->save();
            $invoice_payment_id = $payment->refresh()->id;
            if ($invoice_payment_id != null) {
                $mode = InvoicePayment::paymentModsDisplay[$request->mode];
                $description = "Recorded a sale payment of {$request->amount} via {$mode} for invoice payment id {$invoice_payment_id}";
                $audit_trail_data = ['user_id' => $user->id, 'activity' => $description,
                    'cooperative_id' => $user->cooperative_id];
                Log::info($description);
                event(new AuditTrailEvent($audit_trail_data));

                //complete normal payment
                if (in_array($request->mode,
                    [InvoicePayment::PAYMENT_MODE_CASH, InvoicePayment::PAYMENT_MODE_BANK,
                        InvoicePayment::PAYMENT_MODE_MPESA_OFFLINE])) {
                    if (complete_sale_payment($invoice_payment_id)) {
                        DB::commit();
                        toastr()->success('Success! Payment recorded successfully');
                        return redirect()->back();
                    } else {
                        DB::commit();
                        toastr()->warning('Sale did not complete successfully');
                        return redirect()->back()->withInput();
                    }

                }

                if ($request->mode == InvoicePayment::PAYMENT_MODE_MPESA_STK_PUSH) {
                    $reference = "Sale payment of {$request->amount} via {$mode}";

                    $isFarmer = $sale->farmer_id != null;
                    if ($isFarmer) {
                        $farmer = $sale->farmer;
                        $customer = null;
                    } else {
                        $customer = $sale->customer;
                        $farmer = null;
                    }

                    $this->stk_push($request->phone, $request->amount, $reference, InvoicePayment::class, $invoice_payment_id, $farmer, $customer, $isFarmer);
                    DB::commit();
                    toastr()->success("Request Sent; and is being processed by MPESA");
                    return redirect()->back();
                }

                if ($request->mode == InvoicePayment::PAYMENT_MODE_WALLET) {
                    $wallet = Wallet::where('farmer_id', $sale->farmer_id)->first();
                    if ($wallet == null) {
                        DB::rollBack();
                        toastr()->warning('Wallet balance is 0');
                        return redirect()->back()->withInput();
                    }
                    $amount = $request->amount;
                    $largest_balance = max($wallet->current_balance, $wallet->available_balance);
                    //amount greater than wallet balance
                    if ($amount > $largest_balance) {
                        DB::rollBack();
                        toastr()->warning('Amount is more than maximum amount payable via wallet');
                        return redirect()->back()->withInput();
                    }
                    $this->pay_via_wallet($wallet, $amount, $user, $description);
                    complete_sale_payment($invoice_payment_id);
                    DB::commit();
                    toastr()->success('Success! Payment recorded successfully');
                    return redirect()->back();
                }

            } else {
                DB::rollback();
                toastr()->error('Could not retried payment id. Contact IT for support');
                return redirect()->back()->withInput();
            }

        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            toastr()->error('Failed! An error occurred');
            return redirect()->back()->withInput();
        }
    }

    private function pay_via_wallet(Wallet $wallet, $amount, User $user, $description)
    {
        $current_balance = $wallet->current_balance;
        $available_balance = $wallet->available_balance;
        if ($current_balance > $amount || $available_balance > $amount) {
            Log::info('Purchase Pay via Wallet: Wallet balance is more than amount being paid');
            record_wallet_transaction(
                $amount,
                $wallet->id,
                "Farmer Purchase",
                "FarmerPurchase",
                $description,
                $user->id
            );
            if ($current_balance > $amount) {
                Log::info('Farmer Purchase: Current balance is more than amount being paid');
                $wallet->current_balance -= $amount;
            } else {
                Log::info('Farmer Purchase: Available balance is more than amount being paid');
                $wallet->available_balance -= $amount;
            }
            $wallet->updated_at = Carbon::now();
            $wallet->save();
        }
    }

    //get produts
    public function getProducts()
    {
        $coop = Auth::user()->cooperative->id;
        $products = Product::where('cooperative_id', $coop)->whereHas('collections', function ($query) {
            $query->where('available_quantity', '>', '0');
        })->with('collections')->latest()->get();
        return $products;
    }

    //get manufacturings
    public function getProductions()
    {
        $coop = Auth::user()->cooperative->id;
        $productions = Production::where('cooperative_id', $coop)
            ->where('available_quantity', '>', 1)
            ->with(['finalProduct'])->latest()->get();

        return $productions;
    }

    //quotation
    public function quotation($sale_id)
    {
        //get manufactured products
        $coop = Auth::user()->cooperative->id;
        $productions = Production::whereHas('finalProduct', function ($query) use ($coop) {
            $query->where('cooperative_id', $coop);
        })->with(['finalProduct'])->latest()->get();
        //get collected product
        $collections = Product::where('cooperative_id', $coop)->whereHas('collections', function ($query) {
            $query->where('available_quantity', '>', '0');
        })->with('collections')->latest()->get();
        //get sales
        $items = SaleItem::where('sales_id', $sale_id)->latest()->get();
        $sale = Sale::findOrFail($sale_id);
        return view('pages.cooperative.sales.pos.items', compact('sale', 'collections', 'productions', 'items', 'sale_id'));
    }

    //quotation
    public function quotationPdf($sale_id)
    {
        try {
            //get manufactured products
            $user = Auth::user();
            $coop = $user->cooperative->id;
            //get sales
            $products = SaleItem::with('sale')->where('sales_id', $sale_id)->latest()->get();
            $sale = Sale::where('id', $sale_id)->latest()->first();
            $inv = Inv::where('sale_id', $sale_id)->first();
            $invoice_number = (int)$sale_id;
            if ($inv) {
                $invoice_number = $inv->invoice_number;
            }

            $client = new Party([
                'name' => Auth::user()->cooperative->name,
                'phone' => Auth::user()->cooperative->contact_details,
                'email' => Auth::user()->cooperative->email,
                'custom_fields' => [
                    'address' => Auth::user()->cooperative->address,
                    'Served By' => ucwords(strtolower($sale->user->first_name.' '.$sale->user->other_names)),
                    'Generated By' => ucwords(strtolower($user->first_name.' '.$user->other_names)),
                ],
            ]);
            $customer = new Buyer([
                'name' => $sale->farmer_id ? $sale->farmer->user->first_name . " " . $sale->farmer->user->other_names : $sale->customer->name,
                'custom_fields' => [
                    'email' => $sale->farmer_id ? $sale->farmer->user->email : $sale->customer->email,
                    'address' => $sale->customer ? $sale->customer->address : ($sale->farmer->location ? $sale->farmer->location->name : '') ,
                ],
            ]);

            $items = [];
            $tot_discount = $sale->discount;
            foreach ($products as $key => $item) {
                // (new InvoiceItem())->title('Service 1')->description('Your product or service description')->pricePerUnit(47.79),
                $items[] = (new InvoiceItem())->title($item->manufactured_product_id ? $item->manufactured_product->finalProduct->name : $item->collection->product->name)
                    ->pricePerUnit($item->amount)
                    ->quantity($item->quantity)
                    ->discount($tot_discount)
                    ->units($item->manufactured_product_id ? $item->manufactured_product->unit->name : $item->collection->product->unit->name);
            }

            $notes = [
                $sale->notes,
                $sale->toc
            ];
            $notes = implode("<br>", $notes);
            $invoice_name = $user->cooperative->name . " Quotation";
            $currency = $user->cooperative->currency ?? 'KES';
            $invoice = Invoice::make()
                ->name($invoice_name)
                // ->status(__('invoices::invoice.paid'))
                ->seller($client)
                ->sequence((int)$invoice_number)
                ->serialNumberFormat('QUOTE/{SEQUENCE}-'.$sale->invoices->invoice_count)
                ->buyer($customer)
                ->currencySymbol($currency)
                ->currencyThousandsSeparator(',')
                ->addItems($items)
                ->logo(public_path($user->cooperative->logo ?? 'assets/images/favicon.png'))
                ->notes($notes)
                ->template('quote');

            return $invoice->stream();
        } catch (\Exception $e) {
            Log::info($e);
        }
    }

    //invoice
    public function invoicePdf($sale_id)
    {

        try {
            //get manufactured products
            $user = Auth::user();
            $products = SaleItem::with('sale')->where('sales_id', $sale_id)->latest()->get();
            $sale = Sale::withTrashed()->where('id', $sale_id)->latest()->first();
            $inv = Inv::where('sale_id', $sale_id)->first();
            $invoice_number = (int)$sale_id;
            if ($inv) {
                $invoice_number = $inv->invoice_number;
            }

            $client = new Party([
                'name' => $user->cooperative->name,
                'phone' => $user->cooperative->contact_details,
                'email' => $user->cooperative->email,
                'custom_fields' => [
                    'address' => $user->cooperative->address,
                    'Served By' => ucwords(strtolower($sale->user->first_name.' '.$sale->user->other_names)),
                    'Generated By' => ucwords(strtolower($user->first_name.' '.$user->other_names)),
                ],
            ]);
            $customer = new Buyer([
                'name' => $sale->farmer_id ? $sale->farmer->user->first_name . " " . $sale->farmer->user->other_names : $sale->customer->name,
                'custom_fields' => [
                    'email' => $sale->farmer_id ? $sale->farmer->user->email : $sale->customer->email,
                    'address' => $sale->customer ? $sale->customer->address : ($sale->farmer->location ? $sale->farmer->location->name : '') ,
                ],
            ]);

            $items = [];

            foreach ($products as $item) {
                $items[] = (new InvoiceItem())->title($item->manufactured_product_id ? $item->manufactured_product->finalProduct->name : $item->collection->product->name)
                    ->pricePerUnit($item->amount)
                    ->quantity($item->quantity)
                    ->discount(0)
                    ->units($item->manufactured_product_id ? $item->manufactured_product->unit->name : $item->collection->product->unit->name);
            }

            $notes = [
                $sale->notes,
                $sale->toc
            ];
            $notes = implode("<br>", $notes);
            $invoice_name = $user->cooperative->name . " Invoice";
            $currency = $user->cooperative->currency ?? 'KES';
            $invoice = Invoice::make()
                ->name($invoice_name)
                ->status($inv->status == 1 ? __('invoices::invoice.paid') : '')
                ->seller($client)
                ->totalDiscount($sale->discount)
                ->sequence((int)$invoice_number)
                ->serialNumberFormat('INV/{SEQUENCE}-' . $sale->invoices->invoice_count)
                ->buyer($customer)
                ->currencySymbol($currency)
                ->currencyThousandsSeparator(',')
                ->addItems($items)
                ->logo(public_path($user->cooperative->logo ?? 'assets/images/favicon.png'))
                ->notes($notes)
                ->template('pos');

            return $invoice->stream();
        } catch (\Exception $e) {
            Log::info($e);
        }
    }

    //invoice payment receipt
    public function invoicePayReceipt($sale_id)
    {
        try {
            //get manufactured products
            $user = Auth::user();
            $sale = Sale::where('id', $sale_id)->latest()->first();
            $inv = $sale->invoices;
            $client = new Party([
                'name' => $user->cooperative->name,
                'phone' => $user->cooperative->contact_details,
                'email' => $user->cooperative->email,
                'custom_fields' => [
                    'address' => $user->cooperative->address,
                ],
            ]);
            $customer = new Buyer([
                'name' => $sale->farmer_id ? $sale->farmer->user->first_name . " " . $sale->farmer->user->other_names : $sale->customer->name,
                'custom_fields' => [
                    'email' => $sale->farmer_id ? $sale->farmer->user->email : $sale->customer->email,
                ],
            ]);

            $items = [];
            foreach ($inv->invoice_payments as $key => $item) {
                // (new InvoiceItem())->title('Service 1')->description('Your product or service description')->pricePerUnit(47.79),
                if ($item->status === InvoicePayment::PAYMENT_STATUS_SUCCESS) {
                    $items[] = (new InvoiceItem())->title($item->transaction_number)
                        ->description(InvoicePayment::paymentModsDisplay[$item->payment_platform])
                        ->pricePerUnit($item->amount)
                        ->quantity(1)
                        ->discount(0)
                        ->units($item->created_at->format('Y-m-d'));
                }

            }

            $notes =
                'This receipt is system generated';
            $invoice_name = $user->cooperative->name . " Receipt";
            $currency = $user->cooperative->currency;
            $invoice = Invoice::make()
                ->name($invoice_name)
                ->status(__('invoices::invoice.paid'))
                ->seller($client)
                ->sequence($inv->invoice_number)
                ->serialNumberFormat('RS/{SEQUENCE}-'.$inv->invoice_count)
                ->buyer($customer)
                ->currencySymbol($currency)
                ->currencyThousandsSeparator(',')
                ->addItems($items)
                ->logo(public_path(Auth::user()->cooperative->logo ?? 'assets/images/favicon.png'))
                ->notes($notes)
                ->template('receipt');

            return $invoice->stream();
        } catch (\Exception $e) {
            Log::info($e);
        }
    }
    //
    //mail pdf
    public function mailPdf($sale_id)
    {
        //get manufactured products
        $coop = Auth::user()->cooperative->id;
        //get sales
        $items = SaleItem::where('sales_id', $sale_id)->latest()->get();
        $sale = Sale::where('id', $sale_id)->latest()->first();
        $data = ['sale' => $sale, 'items' => $items];
        $pdf = PDF::loadView('pdfs.sales.quotation', compact('data', 'sale_id'));
        // $message = 'Hello. Please find the attached quotation.';

        Mail::send('pdfs.sales.quotation', $data, function ($message) use ($data, $pdf) {
            $message->to($data['sale']->farmer_id ? $data['sale']->farmer->user->email : $data['sale']->customer->email, $data['sale']->farmer_id ? $data['sale']->farmer->user->email : $data['sale']->customer->email)
                ->subject(" Quotation ")
                ->attachData($pdf->output(), "Sales_quotation.pdf");
        });
        toastr()->success('Sent!');
        return back();
    }

    //add sale
    public function storeSale(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'buyers' => 'required',
            'due_date' => 'required'
        ], [
            'due_date.required' => 'Due date is required',
            'buyers.required' => 'Select who to sell to',
        ]);
        try {

            DB::beginTransaction();
            $user = Auth::user();
            $coop = $user->cooperative->id;
            $sale = new Sale();

            if ($request->farmer) {
                $sale->farmer_id = $request->farmer;
            } else if ($request->customer) {
                $sale->customer_id = $request->customer;
            } else {
                return response()->json('Select who is buying first', 400);
                toastr()->error('Select who is buying first');
                return back();
            }
            $saleSequenceId = $this->generateSaleOrInvoiceId($coop, 'sale');

            $sale->user_id = $user->id;
            $sale->cooperative_id = $coop;
            $sale->sale_batch_number = $saleSequenceId['sequence'];
            $sale->type = $request->type;
            $sale->notes = $request->notes;
            $sale->toc = $request->toc;
            $sale->save_type = $request->save_type;
            $sale->discount = $request->discount ?? 0;
            $sale->date = $request->due_date ? \Carbon\Carbon::create($request->due_date) : Carbon::now()->addDays(30);
            $sale->sale_count = $saleSequenceId['number'];
            $sale->save();

            //invoice
            $invoiceSequenceAndCount = $this->generateSaleOrInvoiceId($coop, 'invoice');
            $invoice = new Inv();
            $invoice->invoice_number = $invoiceSequenceAndCount['sequence'];
            $invoice->invoice_count = $invoiceSequenceAndCount['number'];
            $invoice->sale_id = $sale['id'];
            $invoice->status = Inv::STATUS_UNPAID;
            $invoice->delivery_status = Inv::DELIVERY_STATUS_PENDING;
            $invoice->date = $request->due_date ? \Carbon\Carbon::create($request->due_date) : now()->addDays(30);
            $invoice->save();

            $saleAmount = 0;

            //add items
            if ($request->items) {
                foreach ($request->items as $item) {
                    $sale_item = new SaleItem();
                    if ($item['what_to_sell'] == 1) {
                        $sale_item->collection_id = $item['product'];
                    } else {
                        $sale_item->manufactured_product_id = $item['manufactured'];
                    }
                    $sale_item->amount = $item['amount'];
                    $sale_item->quantity = $item['quantity'];
                    $sale_item->discount = $item['discount'] ?? 0;
                    $sale_item->sales_id = $sale['id'];
                    $sale_item->save();

                    $saleAmount += $item['amount'] * $item['quantity'];
                }
                toastr()->success(ucfirst($request->type) . ' created successfully');
            } else {
                return response()->json('Add items to proceed', 400);
            }
            $sale->balance = $saleAmount - $sale->discount;
            $sale->save();

            DB::commit();
            return response()->json(['id' => $sale['id']]);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            toastr()->error('Failed! An error occurred');
            return response()->json('Failed! An error occured', 400);
        }
    }


    public function markGoodsAsDelivered($invoiceId): \Illuminate\Http\RedirectResponse
    {

        $invoice = Inv::findOrFail($invoiceId);
        if ($invoice == null) {
            Log::info("Invoice {$invoiceId} is null");
            toastr()->warning('Invalid Invoice Id provided');
            return redirect()->back();
        }

        //check due date
        $today = Carbon::now()->subDay();
        $dueDate = Carbon::parse($invoice->date);

        if ($today->gt($dueDate)) {
            toastr()->warning("Invoice is already due, kindly consider voiding it");
            Log::warning("Invoice {$invoiceId} is due, cannot be marked as delivered");
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            $invoice->delivery_status = Inv::DELIVERY_STATUS_DELIVERED;
            $invoice->updated_at = Carbon::now();
            $invoice->save();
            if (update_stock($invoice->sale)) {
                Log::info("Invoice {$invoiceId} Goods delivered.");

                // credit sale && inventory adjustment
                $balance = $invoice->sale->balance;
                create_account_transaction('Credit Sales', $balance, "Credit sales for invoice no. {$invoice->invoice_number}");
                create_account_transaction('Inventory Sale', $balance, "Issue inventory for invoice no. {$invoice->invoice_number}");

                DB::commit();
                toastr()->success('Stock Updated Successfully!');
                return redirect()->back();
            } else {
                Log::error("Invoice {$invoiceId} stock update failed");
                DB::rollBack();
                toastr()->error('Stock Update Failed!');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Log::error("Invoice {$invoiceId} delivery failed, {$th->getMessage()}");
            DB::rollBack();
            toastr()->error('Oops! Operation Failed!');
            return redirect()->back();
        }
    }


    /**
     * Generate invoice sequence and sale sequence with their ids
     * @param $cooperative
     * @param $type
     * @return array
     */
    private function generateSaleOrInvoiceId($cooperative, $type)
    {
        if ($type == 'sale') {
            $latestSale = Sale::withTrashed()->where('cooperative_id', $cooperative)->orderBy('sale_count', 'desc')->first();
            if ($latestSale) {
                $nextNumber = $latestSale->sale_count + 1;
            } else {
                $nextNumber = 1;
            }

        } else {
            $latestInvoiceNumber = \App\Invoice::withTrashed()
                ->join('sales', 'sales.id', '=', 'invoices.sale_id')
                ->where('sales.cooperative_id', $cooperative)
                ->orderBy('invoice_count', 'desc')
                ->first();
            if ($latestInvoiceNumber) {
                $nextNumber = $latestInvoiceNumber->invoice_count + 1;
            } else {
                $nextNumber = 1;
            }
        }

        return [
            "sequence" => date('Ymd'),
            "number" => $nextNumber
        ];
    }

    //change to invoice
    public function convertToInvoice($id)
    {
        try {
            DB::beginTransaction();
            //get sale
            $sale = Sale::with('saleItems')->find($id);
            $sale->type = 'sale';
            $sale->save();
            DB::commit();
            toastr()->success('Invoice created successfully');
            return back();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            toastr()->error('Failed! An error occurred');
            return back();
        }

    }

    //voide sale/quotation
    public function voidSale($id)
    {
        try {
            //get sale
            $sale = Sale::with('saleItems')->find($id);
            //should consider updating the sales items.
            $sale->delete();
            toastr()->warning('Invoice voided!');
            return back();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    //make sale

    public function addSaleItems(Request $request, $saleId)
    {
        $request->validate([
            'amount' => 'required',
            'quantity' => 'required',
            'what_to_sell' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $coop = $user->cooperative->id;
            $sale_item = new SaleItem();
            if ($request->what_to_sell == 1) {
                $sale_item->collection_id = $request->product;
            } else {
                $sale_item->manufactured_product_id = $request->manufactured;
            }
            $sale_item->amount = $request->amount;
            $sale_item->quantity = $request->quantity;
            $sale_item->discount = $request->discount ?? 0;
            $sale_item->sales_id = $saleId;
            $sale_item->save();

            $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Sales item for sale id '.$saleId
                . $request->amount . ' amount sale item id', 'cooperative_id' => $coop];
            event(new AuditTrailEvent($audit_trail_data));
            DB::commit();
            toastr()->success('Sale Item recorded successfully');
            return back();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            toastr()->error('Failed! An error occurred');
            return back();
        }

    }

    public function deleteSaleItem($itemId)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $saleItem = SaleItem::findOrFail($itemId);
            $saleItemsCount = SaleItem::withoutTrashed()->where('sales_id', $saleItem->sales_id)->count();
            if ($saleItemsCount == 1) {
                toastr()->warning('Only 1 item in the invoice/quotation, consider voiding the invoice');
                return redirect()->back();
            }
            $saleItem->delete();
            $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Deleted sale item: ' . $itemId, 'cooperative_id' => $user->cooperative_id];
            event(new AuditTrailEvent($audit_trail_data));
            DB::commit();
            toastr()->success('Item removed!');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            toastr()->error('Failed! An error occurred');
            return back();
        }
    }

    public function updateDiscount(Request $request, $saleId)
    {
        $request->validate([
            'discount' => 'integer|required'
        ]);
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $sale = Sale::findOrFail($saleId);
            $oldDiscount = $sale->discount;
            if ($request->discount > $request->sale_amount) {
                toastr()->warning('Discount cannot be more than the total sale amount.');
                return redirect()->back();
            }
            $sale->balance -= abs($sale->discount - $request->discount);
            $sale->discount = $request->discount;
            $sale->save();
            $audit_trail_data = ['user_id' => $user->id, 'activity' => "Updated discount of sale id {$saleId} from {$oldDiscount} to {$request->discount} : ", 'cooperative_id' => $user->cooperative_id];
            event(new AuditTrailEvent($audit_trail_data));
            DB::commit();
            toastr()->success('Item removed!');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            toastr()->error('Failed! An error occurred');
            return back();
        }
    }

    public function updateSaleItemPriceAndQuantity(Request $request, $itemId)
    {

        $request->validate([
            'quantity' => 'integer|required|min:1',
            'unit_price' => 'integer|required|min:1'
        ]);
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $saleItem = SaleItem::findOrFail($itemId);
            $oldQuantity = $saleItem->quantity;
            $oldUnitPrice = $saleItem->amount;
            $saleItem->quantity = $request->quantity;
            $saleItem->amount = $request->unit_price;
            $saleItem->save();
            $sale = $saleItem->sale;
            $saleId = $sale->id;
            $otherSaleItems = DB::select("SELECT SUM( quantity * amount) as amount FROM sale_items WHERE sales_id = '$saleId' AND id <> '$itemId' GROUP BY sales_id");

            if ($otherSaleItems) {
                $sale->balance = ($otherSaleItems[0]->amount + ($saleItem->quantity * $saleItem->amount)) - $sale->discount;
                $sale->save();
            } else {
                $sale->balance = ($saleItem->quantity * $saleItem->amount) - $sale->discount;
                $sale->save();
            }
            $auditLog = "updated saleId: {$itemId} unit price: {$request->unit_price} from {$oldUnitPrice} and quantity {$request->quantity} from {$oldQuantity} ";
            $audit_trail_data = ['user_id' => $user->id, 'activity' => $auditLog, 'cooperative_id' => $user->cooperative_id];
            event(new AuditTrailEvent($audit_trail_data));
            DB::commit();
            toastr()->success('Item unit price and quantity updated!');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            toastr()->error('Failed! An error occurred');
            return back();
        }
    }


    public function returned_items(Request $request)
    {
        $user = Auth::user();
        $items = returned_items_data($user->cooperative_id, $request)->limit(100)->get();
        $unpaidStatus = Inv::STATUS_UNPAID;
        $deliveredStatus = Inv::DELIVERY_STATUS_DELIVERED;
        $partially_paid = Inv::STATUS_PARTIAL_PAID;
        $fully_paid_status = Inv::STATUS_PAID;
        $date = Carbon::now()->subDay();
        $coop = $user->cooperative->id;
        $sales = DB::select("
                SELECT s.id, CONCAT(i.invoice_number, '-', i.invoice_count) as invoice_number  FROM sales s
                JOIN invoices i on s.id = i.sale_id
                WHERE s.cooperative_id = '$coop' AND i.date > '$date' AND i.deleted_at IS NULL AND i.delivery_status = '$deliveredStatus' AND (i.status = '$unpaidStatus' OR  i.status = '$partially_paid' OR i.status = '$fully_paid_status')
        ");

        $customers = Customer::latest()->get();
        //get farmers
        $farmers = User::where('cooperative_id', $user->cooperative_id)->whereHas('farmer')->with(['farmer'])->get();
        return view('pages.cooperative.sales.pos.returned_items', compact('sales', 'items', 'farmers', 'customers'));
    }

    public function get_sale_items($saleId)
    {
        $sale_items = DB::select("
            SELECT fp.name AS manufactured_product_name, p.id AS production_id, c.id AS collection_id, pr.name AS product_name FROM sale_items si
            LEFT JOIN productions p ON si.manufactured_product_id = p.id
            LEFT JOIN collections c ON si.collection_id = c.id
            LEFT JOIN final_products fp ON p.final_product_id = fp.id
            LEFT JOIN products pr ON c.product_id = pr.id
            WHERE si.sales_id = '$saleId'
        ");

        return json_encode($sale_items);
    }


    public function record_returned_items(Request $request)
    {

        $request->validate([
            'invoice' => 'required', // sale id
            'type' => 'required',
            'manufactured_product' => 'sometimes|nullable|required_if:type,==,1',
            'collection' => 'sometimes|nullable|required_if:type,==,2',
            'quantity' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $sale = Sale::findOrFail($request->invoice);
        $user = Auth::user();

        if($request->type == 1){
            $saleItem = SaleItem::where('manufactured_product_id',$request->manufactured_product)
                ->where('sales_id', $sale->id)
                ->first();
            $quantity = $saleItem->quantity;
            $amount = $quantity * $saleItem->amount;
        }else{
            $saleItem = SaleItem::where('collection_id',$request->collection)
                ->where('sales_id', $sale->id)
                ->first();
            $quantity = $saleItem->quantity;
            $amount = $quantity * $saleItem->amount;
        }

        if($request->quantity > $quantity){
            $quantityMessage = "Provided quantity is more than the quantity on the invoice $quantity";
            toastr()->error($quantityMessage);
            return redirect()->back()->withInput()->withErrors(["quantity" => $quantityMessage]);
        }


        if($request->amount > $amount){
            $amountMessage = "Provided amount is more than the amount on the invoice $amount";
            toastr()->error($amountMessage);
            return redirect()->back()->withInput()->withErrors(["amount" => $amountMessage]);
        }

        try{
            DB::beginTransaction();
            $returned_item = new ReturnedItem();
            $returned_item->date = Carbon::now()->format('Y-m-d');
            $returned_item->sale_id = $sale->id;
            $returned_item->collection_id = $request->collection;
            $returned_item->manufactured_product_id = $request->manufactured_product;
            $returned_item->quantity = $request->quantity;
            $returned_item->notes = $request->notes;
            $returned_item->cooperative_id = $user->cooperative_id;
            $returned_item->served_by_id = $user->id;
            $returned_item->amount = $request->amount;
            $returned_item->save();
            $sale->balance -= $request->amount;
            $sale->save();
            $invoice = $sale->invoices;
            if($sale->balance == 0){
                $invoice->status = Inv::STATUS_PAID;
            }else{
                $invoice->status = Inv::STATUS_RETURNS_RECORDED;
            }

            if($sale->farmer_id){
                $wallet = Wallet::where('farmer_id', $sale->farmer_id)->first();
                if ($wallet) {
                    //update current balance
                    $wallet->available_balance += $request->amount;
                    $wallet->save();
                } else {
                    //create farmer wallet
                    $wallet = default_wallet($sale->farmer_id, $request->amount);
                }
                $prefix = "Sales Batch ".$sale->sale_batch_number.'-'.$sale->sale_count;
                record_wallet_transaction(
                    $request->amount,
                    $wallet->id,
                    "Sales Returned",
                    $prefix,
                    "Sales Returns",
                    $user->id);
            }

            $invoice->save();

            $description = "Returned goods for invoice number {$sale->invoices->invoice_number}-{$sale->invoices->invoice_count}";

            create_account_transaction('Sales Returns', $amount, $description);
            create_account_transaction('Inventory Returns', $amount, $description);

            $audit_trail_data = ['user_id' => $user->id, 'activity' => "Invoice id {$sale->invoices->id} Goods returned", 'cooperative_id' => $user->cooperative_id];
            event(new AuditTrailEvent($audit_trail_data));
            DB::commit();
            toastr()->success('Returns recorded Successfully');
            return redirect()->back();

        }catch (\Exception $ex){
            DB::rollBack();
            toastr()->error('Oops, operation failed!');
            return redirect()->back()->withInput();
        }

    }


    public function export_report_sale(Request $request, $type)
    {

        $user = Auth::user();
        $coop = $user->cooperative_id;
        $request["date"] = $request->dates;
        $request["farmer"] = $request->farmer ? explode(",",$request["farmer"]) : null;
        $request["customer"] = $request->customer ? explode(",",$request["customer"]) : null;
        $sale_type = $request->sale_type ?? 'sale';
        $sales = sales_base_query($coop, $request, $sale_type)->latest()->get();
        $file_name_prefix = 'sales_' . $request->type . '_' . date('d') . '_' . date('m') . '_' . date('Y');
        if ($type != env('PDF_FORMAT')) {
            $file_name = $file_name_prefix . '.' . $type;
            return Excel::download(new SalesExport($sales), $file_name);
        } else {
            $data = [
                'title' => $request->title,
                'pdf_view' => 'sales',
                'records' => $sales,
                'filename' => $file_name_prefix,
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function export_report_quotation(Request $request, $type)
    {
        $user = Auth::user();
        $coop = $user->cooperative_id;
        $request["date"] = $request->dates;
        $request["farmer"] = $request->farmer ? explode(",",$request["farmer"]) : null;
        $request["customer"] = $request->customer ? explode(",",$request["customer"]) : null;
        $sales = quotation_base_query($coop, $request)->latest()->get();
        $file_name_prefix = 'sales_quotation_' . $request->type . '_' . date('d') . '_' . date('m') . '_' . date('Y');
        if ($type != env('PDF_FORMAT')) {
            $file_name = $file_name_prefix . '.' . $type;
            return Excel::download(new SalesQuotationExport($sales), $file_name);
        } else {
            $data = [
                'title' => 'Quotation',
                'pdf_view' => 'quote',
                'records' => $sales,
                'filename' => $file_name_prefix,
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function export_returned_goods(Request $request, $type)
    {
        $request["farmer"] = $request->farmer ? explode(",",$request["farmer"]) : null;
        $request["customer"] = $request->customer ? explode(",",$request["customer"]) : null;
        $user = Auth::user();
        $coop = $user->cooperative_id;
        $request["date"] = $request->dates;
        $items = returned_items_data($coop, $request)->get();
        $file_name_prefix = 'returned_goods_' . $request->type . '_' . date('d') . '_' . date('m') . '_' . date('Y');
        if ($type != env('PDF_FORMAT')) {
            $file_name = $file_name_prefix . '.' . $type;
            return Excel::download(new SalesReturnExport($items), $file_name);
        } else {
            $data = [
                'title' => 'Returned Goods',
                'pdf_view' => 'returned_goods',
                'records' => $items,
                'filename' => $file_name_prefix,
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

}
