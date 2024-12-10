<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\Account;
use App\Customer;
use App\Exports\InvoiceExport;
use App\Exports\QuotationExport;
use App\FinalProduct;
use App\Http\Controllers\Controller;
use App\MilledInventory;
use App\NewInvoice;
use App\NewInvoiceItem;
use App\Quotation;
use App\QuotationItem;
use App\Receipt;
use App\ReceiptItem;
use App\Sale;
use App\SaleItem;
use App\Product;
use App\ProductCategory;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Log;
use Barryvdh\DomPDF\Facade as PDF;
use Excel;
use Invoice;
use App\Exports\SaleExport;
use App\Exports\CustomersExport;

class InventoryAuctionController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function list_customers()
    {
        $user = Auth::user();
        try {
            $coop = $user->cooperative;  //$coop_id = $user->cooperative->id;
            $coop_id = $coop->id;
        } catch (\Throwable $th) {
            $coop_id = null;
        }

        $customers = Customer::where("cooperative_id", $coop_id)->whereNotNull("published_at")->get();

        return view('pages.cooperative-admin.inventory-auction.customers.index', compact('customers'));
    }

    public function add_customer()
    {
        $user = Auth::user();
        try {
            $coop_id = $user->cooperative->id;
        } catch (\Throwable $th) {
            $coop_id = null;
        }

        $exists = Customer::where("cooperative_id", $coop_id)->where("published_at", null)->exists();
        if (!$exists) {
            $draftCustomer = new Customer();
            $draftCustomer->miller_id = $coop_id;
            $draftCustomer->save();
        }

        $draftCustomer = Customer::where("cooperative_id", $coop_id)->where("published_at", null)->firstOrFail();

        return redirect()->route("cooperative-admin.inventory-auction.view-update-customer-details", $draftCustomer->id);
        
    }

    public function view_update_customer_details($id)
    {
        $customer = Customer::find($id);

        return view('pages.cooperative-admin.inventory-auction.customers.update-customer-details', compact('customer'));
    }

    public function update_customer_details(Request $request)
    {
        $user = Auth::user();
        try {
            $coop_id = $user->cooperative->id;
        } catch (\Throwable $th) {
            $coop_id = null;
        }

        $request->validate([
            "customer_id" => "required|exists:customers,id",
            "title" => "required",
            "name" => "required",
            "gender" => "required",
            "email" => ["required", "email", Rule::unique('customers')->where(function ($query) use ($coop_id, $request) {
                return $query->where("email", $request->email)->where("cooperative_id", $coop_id);
            })->ignore($request->customer_id)],
            "phone_number" => ["required", Rule::unique('customers')->where(function ($query) use ($coop_id, $request) {
                return $query->where("phone_number", $request->phone_number)->where("cooperative_id", $coop_id);
            })->ignore($request->customer_id)],
            "address" => "required",
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::find($request->customer_id);
            $customer->title = $request->title;
            $customer->name = $request->name;
            $customer->gender = $request->gender;
            $customer->email = $request->email;
            $customer->phone_number = $request->phone_number;
            $customer->address = $request->address;

            if ($request->has("save_and_publish")) {
                $customer->published_at = Carbon::now();
                toastr()->success('Customer published successfully.');
            }
            $customer->save();

            DB::commit();
            toastr()->success('Customer saved successfully.');
            //return redirect()->back();
            return redirect()->route("cooperative-admin.inventory-auction.list-customers");
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
        
    }

    public function view_customer($id, Request $request)
    {
        $user = Auth::user();
        try {
            $coop_id = $user->cooperative->id;
        } catch (\Throwable $th) {
            $coop_id = null;
        }

        $customer = Customer::find($id);

        $tab = $request->query("tab", "quotations");

        $quotations = [];
        if ($tab == 'quotations') {
            $quotations = Quotation::where("cooperative_id", $coop_id)->where("customer_id", $id)->get();
        }

        $invoices = [];
        if ($tab == 'invoices') {
            $invoices = NewInvoice::where("cooperative_id", $coop_id)->where("customer_id", $id)->get();
        }

        $receipts = [];
        if ($tab == 'receipts') {
            $receipts = DB::table('receipts')
                    ->join('customers as cust', 'receipts.customer_id', '=', 'cust.id')
                    ->where('cust.cooperative_id', $coop_id) // Use the cooperative_id from the customers table
                    ->where('receipts.customer_id', $id)
                    ->select('receipts.*', 'cust.name as customer_name', 'cust.email as customer_email') // Add desired customer fields
                    ->get();
        }

        return view('pages.cooperative-admin.inventory-auction.customers.detail', compact('customer', 'tab', 'quotations', 'invoices', 'receipts'));
    }

    public function retrieve_final_product($id)
    {
        $finalProduct = FinalProduct::find($id);
        return response()->json($finalProduct);
    }

    public function retrieve_milled_inventory($id)
    {
        $milledInventory = MilledInventory::find($id);
        return response()->json($milledInventory);
    }

    public function list_quotations(Request $request)
    {
        $user = Auth::user();
        try {
            $coop_id = $user->cooperative->id;
        } catch (\Throwable $th) {
            $coop_id = null;
        }
        $user_id = Auth::id();


        $isAddingQuotation = $request->query("is_adding_quotation", "0");
        $viewingQuotationId = $request->query("viewing_quotation_id", "");

        $draftQuotation = null;
        $customers = [];
        $finalProducts = [];
        $milledInventories = [];
        if ($isAddingQuotation == "1") {
            $customers = Customer::where("cooperative_id", $coop_id)->whereNotNull("published_at")->get();

            $finalProducts = FinalProduct::where("cooperative_id", $coop_id)->get();

            $milledInventories = MilledInventory::where("cooperative_id", $coop_id)->get();

            $draftExists = Quotation::where("user_id", $user_id)->where("cooperative_id", $coop_id)->where("published_at", null)->exists();
            if (!$draftExists) {
                $now = Carbon::now();
                $quotationNumber = "QTN";
                $quotationNumber .= $now->format('Ymd');

                // count today's inventories
                $todaysInventories = Sale::where(DB::raw("DATE(created_at)"), $now->format('Y-m-d'))->count();
                $quotationNumber .= str_pad($todaysInventories + 1, 3, '0', STR_PAD_LEFT);

                $draftQuotation = new Quotation();
                $draftQuotation->quotation_number = $quotationNumber;
                $draftQuotation->user_id = $user_id;
                $draftQuotation->miller_id = $coop_id;
                $draftQuotation->save();
            } else {
                $draftQuotation = Quotation::where("user_id", $user_id)->where("cooperative_id", $coop_id)->where("published_at", null)->firstOrFail();
            }
        }
        if (!empty($viewingQuotationId)) {
            $customers = Customer::where("cooperative_id", $coop_id)->whereNotNull("published_at")->get();

            $finalProducts = FinalProduct::where("cooperative_id", $coop_id)->get();

            $milledInventories = MilledInventory::where("cooperative_id", $coop_id)->get();
            $draftQuotation = Quotation::where("id", $viewingQuotationId)->firstOrFail();
        }

        $quotations = Quotation::whereNotNull("published_at")->get();

        return view('pages.cooperative-admin.inventory-auction.quotation', compact("isAddingQuotation", "viewingQuotationId", "draftQuotation", "customers", "finalProducts", "milledInventories", "quotations"));
    }

    public function export_many_quotations(Request $request, $type)
    {

        $export_status = $request->query("export_status", "all");
        $start_date = $request->query("start_date");
        $end_date = $request->query("end_date");
        
       
        $user = Auth::user();
        $coop_id = null;
        $image=null;
        if ($user) {
            $coop_id = $user->cooperative->id;
            $image=$user->profile_picture;
        }

        // $rawQuotations = Quotation::whereNotNull("published_at")->where("cooperative_id", $coop_id)->get();
        $rawQuotations = Quotation::where("created_at", ">=", $start_date)->where("created_at", "<=", $end_date)->get();

        $quotations = [];
        // todo: format data
        foreach ($rawQuotations as $quotation) {

            $status = 'Invoice Pending';
            if ($quotation->has_invoice) {
                $status = 'Complete';
            } else if ($quotation->expires_at != '' && $quotation->expires_at < now()) {
                $status = 'Expired';
            }

            if ($export_status) {
                if ($status != $export_status && $export_status != 'all') {
                    continue;
                }
            }


            $quotations[] = [
                "quotation_number" => $quotation->quotation_number,
                "customer_name" => $quotation->customer->name,
                "customer_email" => $quotation->customer->email,
                "items_count" => $quotation->items_count,
                "total_price" => $quotation->total_price,
                "status" => $status,
                "created_at" => $quotation->created_at,
            ];
        }


        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('final_products_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new QuotationExport($quotations), $file_name);
        } else {
            $columns = [
                ['name' => 'Quotation Number', 'key' => "quotation_number"],
                ['name' => 'Customer Name', 'key' => "customer_name"],
                ['name' => 'Customer Email', 'key' => "customer_email"],
                ['name' => 'Items Count', 'key' => "items_count"],
                ['name' => 'Total Price', 'key' => "total_price"],
                ['name' => 'Status', 'key' => "status"],
                ['name' => 'Created At', 'key' => "created_at"],
            ];

            $imagePath = public_path('storage/' . $image); // Absolute path to image

            $data = [
                'title' => 'Quotations',
                'pdf_view' => 'quotations',
                'records' => $quotations,
                'image'=>$imagePath,
                'filename' => strtolower('quotations_' . date('d_m_Y')),
                'orientation' => 'letter',
            ];
            return download_pdf($columns, $data);
        }
    }

    public function save_quotation_item(Request $request)
    {
        $request->validate([
            "quotation_id" => "required|exists:quotations,id",
            "item_type" => "required",
            "price" => "required|numeric",
            "quantity" => "required|numeric",
        ]);

        DB::beginTransaction();
        try {
            // todo: validate item in stock
            $quotationItem = new QuotationItem();
            $quotationItem->quotation_id = $request->quotation_id;

            if ($request->item_type == "Final Product") {
                $quotationItem->item_id = $request->final_product_item_id;
            } else {
                $quotationItem->item_id = $request->milled_inventory_item_id;
            }

            $quotationItem->item_type = $request->item_type;
            $quotationItem->price = $request->price;
            $quotationItem->quantity = $request->quantity;
            $quotationItem->save();


            DB::commit();
            toastr()->success('Quotation item saved');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function delete_quotation_item($id)
    {

        DB::beginTransaction();

        try {
            QuotationItem::find($id)->delete();

            DB::commit();
            toastr()->success('Quotation item deleted');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function publish_quotation()
    {
        $user = Auth::user();
        try {
            $coop_id = $user->cooperative->id;
        } catch (\Throwable $th) {
            $coop_id = null;
        }
        $user_id = Auth::id();

        DB::beginTransaction();

        try {
            $quotation = Quotation::where("cooperative_id", $coop_id)->where("user_id", $user_id)->where("published_at", null)->firstOrFail();
            $quotation->published_at = Carbon::now();
            $quotation->save();


            DB::commit();
            toastr()->success('Quotation published successfully');
            return redirect()->route("cooperative-admin.inventory-auction.list-quotations");
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function create_invoice_from_quotation($id)
    {
        $user = Auth::user();
        try {
            $coop_id = $user->cooperative->id;
        } catch (\Throwable $th) {
            $coop_id = null;
        }
        $user_id = Auth::id();

        DB::beginTransaction();
        try {
            $quotation = Quotation::find($id);

            $now = Carbon::now();
            $invoiceNumber = "INV";
            $invoiceNumber .= $now->format('Ymd');

            // count today's invoices
            $todaysInvoices = NewInvoice::where(DB::raw("DATE(created_at)"), $now->format('Y-m-d'))->count();
            $invoiceNumber .= str_pad($todaysInvoices + 1, 3, '0', STR_PAD_LEFT);

            // create invoice
            $invoice = new NewInvoice();
            $invoice->invoice_number = $invoiceNumber;
            $invoice->quotation_id = $quotation->id;
            $invoice->user_id = $user_id;
            $invoice->miller_id = $coop_id;
            $invoice->customer_id = $quotation->customer_id;
            $invoice->published_at = $now;
            $invoice->save();

            // create invoice items
            foreach ($quotation->items as $item) {
                $invoiceItem = new NewInvoiceItem();
                $invoiceItem->new_invoice_id = $invoice->id;
                $invoiceItem->item_id = $item->item_id;
                $invoiceItem->item_type = $item->item_type;
                $invoiceItem->price = $item->price;
                $invoiceItem->quantity = $item->quantity;
                $invoiceItem->save();
            }

            DB::commit();
            toastr()->success('Invoice created successfully');
            return redirect()->route("cooperative-admin.inventory-auction.list-quotations");
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function save_quotation_basic_details(Request $request)
    {
        $request->validate([
            "quotation_id" => "required|exists:quotations,id",
            "customer_id" => "required|exists:customers,id",
        ]);

        DB::beginTransaction();
        try {
            $quotation = Quotation::find($request->quotation_id);
            $quotation->customer_id = $request->customer_id;
            $quotation->expires_at = $request->expires_at;
            $quotation->save();


            DB::commit();
            toastr()->success('Basic Quotation Details saved successfully.');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function list_invoices(Request $request)
    {
        $user = Auth::user();
        try {
            $coop_id = $user->cooperative->id;
        } catch (\Throwable $th) {
            $coop_id = null;
        }
        $user_id = Auth::id();


        $isAddingInvoice = $request->query("is_adding_invoice", "0");
        $viewingInvoiceId = $request->query("viewing_invoice_id", "");

        $draftInvoice = null;
        $customers = [];
        $finalProducts = [];
        $milledInventories = [];
        if ($isAddingInvoice == "1") {
            $customers = Customer::where("cooperative_id", $coop_id)->whereNotNull("published_at")->get();

            $finalProducts = FinalProduct::where("cooperative_id", $coop_id)->get();

            $milledInventories = MilledInventory::where("cooperative_id", $coop_id)->get();

            $draftExists = NewInvoice::where("user_id", $user_id)->where("cooperative_id", $coop_id)->where("published_at", null)->exists();
            if (!$draftExists) {
                $now = Carbon::now();
                $invoiceNumber = "INV";
                $invoiceNumber .= $now->format('Ymd');

                // count today's inventories
                $todaysInventories = Sale::where(DB::raw("DATE(created_at)"), $now->format('Y-m-d'))->count();
                $invoiceNumber .= str_pad($todaysInventories + 1, 3, '0', STR_PAD_LEFT);

                $draftInvoice = new NewInvoice();
                $draftInvoice->invoice_number = $invoiceNumber;
                $draftInvoice->user_id = $user_id;
                $draftInvoice->miller_id = $coop_id;
                $draftInvoice->save();
            } else {
                $draftInvoice = NewInvoice::where("user_id", $user_id)->where("cooperative_id", $coop_id)->where("published_at", null)->firstOrFail();
            }
        }

        if (!empty($viewingInvoiceId)) {
            $customers = Customer::where("cooperative_id", $coop_id)->whereNotNull("published_at")->get();

            $finalProducts = FinalProduct::where("cooperative_id", $coop_id)->get();

            $milledInventories = MilledInventory::where("cooperative_id", $coop_id)->get();

            $draftInvoice = NewInvoice::find($viewingInvoiceId);
        }

        $invoices = NewInvoice::whereNotNull("published_at")->get();

        return view('pages.cooperative-admin.inventory-auction.invoice', compact("isAddingInvoice", "viewingInvoiceId", "draftInvoice", "customers", "finalProducts", "milledInventories", "invoices"));
    }

    public function export_many_invoices(Request $request, $type)
    {

        $export_status = $request->query("export_status", "all");
        $start_date = $request->query("start_date");
        $end_date = $request->query("end_date");

        $user = Auth::user();
        $coop_id = null;
        $image=null;
        if ($user) {
            $coop_id = $user->cooperative->id;
            $image=$user->profile_picture;
        }

        $rawInvoices = NewInvoice::where("created_at", ">=", $start_date)->where("created_at", "<=", $end_date)->get();

        $invoices = [];
        // todo: format data
        foreach ($rawInvoices as $invoice) {

            $status = 'Pending';
            if ($invoice->has_receipt) {
                $status = 'Complete';
            } else if ($invoice->expires_at != '' && $invoice->expires_at < now()) {
                $status = 'Expired';
            }

            if ($export_status) {
                if ($status != $export_status && $export_status != 'all') {
                    dd("skip: $status != $export_status && $export_status != 'all'");
                    continue;
                }
            }

            $customer_name = "";
            $customer_email = "";

            if ($invoice->customer) {
                $customer_name = $invoice->customer->name;
                $customer_email = $invoice->customer->email;
            }


            $invoices[] = [
                "invoice_number" => $invoice->invoice_number,
                "customer_name" => $customer_name,
                "customer_email" => $customer_email,
                "items_count" => $invoice->items_count,
                "total_price" => $invoice->total_price,
                "status" => $status,
                "created_at" => $invoice->created_at,
            ];
        }

        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('invoices_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new InvoiceExport($invoices), $file_name);
        } else {
            $columns = [
                ['name' => 'Invoice Number', 'key' => "invoice_number"],
                ['name' => 'Customer Name', 'key' => "customer_name"],
                ['name' => 'Customer Email', 'key' => "customer_email"],
                ['name' => 'Items Count', 'key' => "items_count"],
                ['name' => 'Total Price', 'key' => "total_price"],
                ['name' => 'Status', 'key' => "status"],
                ['name' => 'Created At', 'key' => "created_at"],
            ];
            $imagePath = public_path('storage/' . $image); // Absolute path to image
            $data = [
                'title' => 'Invoices',
                'pdf_view' => 'invoices',
                'records' => $invoices,
                'image' => $imagePath,
                'filename' => strtolower('invoices_' . date('d_m_Y')),
                'orientation' => 'letter',
            ];
            return download_pdf($columns, $data);
        }
    }

    public function save_Invoice_item(Request $request)
    {
        $request->validate([
            "invoice_id" => "required|exists:new_invoices,id",
            "item_type" => "required",
            "price" => "required|numeric",
            "quantity" => "required|numeric",
        ]);

        DB::beginTransaction();
        try {
            // todo: validate item in stock
            $invoiceItem = new NewInvoiceItem();
            $invoiceItem->new_invoice_id = $request->invoice_id;

            if ($request->item_type == "Final Product") {
                $invoiceItem->item_id = $request->final_product_item_id;
            } else {
                $invoiceItem->item_id = $request->miller_inventory_item_id;
            }

            $invoiceItem->item_type = $request->item_type;
            $invoiceItem->price = $request->price;
            $invoiceItem->quantity = $request->quantity;
            $invoiceItem->save();


            DB::commit();
            toastr()->success('Invoice item saved');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function delete_invoice_item($id)
    {

        DB::beginTransaction();

        try {
            NewInvoiceItem::find($id)->delete();

            DB::commit();
            toastr()->success('Invoice item deleted');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function publish_invoice()
    {
        $user = Auth::user();
        try {
            $coop_id = $user->cooperative->id;
        } catch (\Throwable $th) {
            $coop_id = null;
        }
        $user_id = Auth::id();

        DB::beginTransaction();

        try {
            $invoice = NewInvoice::where("cooperative_id", $coop_id)->where("user_id", $user_id)->where("published_at", null)->firstOrFail();
            $invoice->published_at = Carbon::now();
            $invoice->save();


            DB::commit();
            toastr()->success('Invoice published successfully');
            return redirect()->route("cooperative-admin.inventory-auction.list-invoices");
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function save_invoice_basic_details(Request $request)
    {
        $request->validate([
            "invoice_id" => "required|exists:new_invoices,id",
            "customer_id" => "required|exists:customers,id",
        ]);

        DB::beginTransaction();
        try {
            $invoice = NewInvoice::find($request->invoice_id);
            $invoice->customer_id = $request->customer_id;
            $invoice->expires_at = $request->expires_at;
            $invoice->save();


            DB::commit();
            toastr()->success('Invoice basic details saved successfully.');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function create_receipt_from_invoice($id)
    {
        $user = Auth::user();
        try {
            $coop_id = $user->cooperative->id;
        } catch (\Throwable $th) {
            $coop_id = null;
        }
        $user_id = Auth::id();

        DB::beginTransaction();
        try {
            $invoice = NewInvoice::find($id);

            $now = Carbon::now();
            $receiptNumber = "RPT";
            $receiptNumber .= $now->format('Ymd');

            // count today's inventories
            $todaysReceipts = Receipt::where(DB::raw("DATE(created_at)"), $now->format('Y-m-d'))->count();
            $receiptNumber .= str_pad($todaysReceipts + 1, 3, '0', STR_PAD_LEFT);

            // create receipt
            $receipt = new Receipt();
            $receipt->receipt_number = $receiptNumber;
            $receipt->new_invoice_id = $invoice->id;
            $receipt->user_id = $user_id;
            $receipt->miller_id = $coop_id;
            $receipt->customer_id = $invoice->customer_id;
            $receipt->published_at = $now;
            $receipt->save();

            // create receipt items
            foreach ($invoice->items as $item) {
                $receiptItem = new ReceiptItem();
                $receiptItem->receipt_id = $receipt->id;
                $receiptItem->item_id = $item->item_id;
                $receiptItem->item_type = $item->item_type;
                $receiptItem->price = $item->price;
                $receiptItem->quantity = $item->quantity;
                $receiptItem->save();
            }

            DB::commit();
            toastr()->success('Receipt created successfully');
            return redirect()->route("cooperative-admin.inventory-auction.list-invoices");
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function mark_invoice_as_paid($id)
    {
        $user = Auth::user();
        try {
            $coop_id = $user->cooperative->id;
        } catch (\Throwable $th) {
            $coop_id = null;
        }
        // $user_id = Auth::id();

        $invoice = NewInvoice::find($id);

        $customer_id = $invoice->customer_id;

        DB::beginTransaction();
        try {
            $transaction = new Transaction();
            $transaction->created_by = $user->id;

            // get or create customer account
            $customer_acc = Account::where("owner_type", "CUSTOMER")->where("owner_id", $customer_id)->first();
            if (is_null($customer_acc)) {
                $accCount = Account::count();
                $customer_acc = new Account();
                $customer_acc->acc_number = "A" . str_pad($accCount + 1, 5, '0', STR_PAD_LEFT);
                $customer_acc->owner_type = "CUSTOMER";
                $customer_acc->owner_id = $customer_id;

                $customer_acc->credit_or_debit = "CREDIT";
                $customer_acc->save();
            }

            $transaction->sender_type = 'CUSTOMER';
            $transaction->sender_id = $customer_id;
            $transaction->sender_acc_id = $customer_acc->id;


            // get or create miller account
            $miller_acc = Account::where("owner_type", "MILLER")->where("owner_id", $coop_id)->first();
            if (is_null($miller_acc)) {
                $accCount = Account::count();
                $miller_acc = new Account();
                $miller_acc->acc_number = "A" . str_pad($accCount + 1, 5, '0', STR_PAD_LEFT);
                $miller_acc->owner_type = "MILLER";
                $miller_acc->owner_id = $coop_id;

                $miller_acc->credit_or_debit = "CREDIT";
                $miller_acc->save();
            }

            $transaction->recipient_type = 'MILLER';
            $transaction->recipient_id = $coop_id;
            $transaction->recipient_acc_id = $miller_acc->id;

            // get transaction number
            $now = Carbon::now();
            $transactionNumber = "T";
            $transactionNumber .= $now->format('Ymd');
            // count today's transactions
            $todaysTransactions = Transaction::where(DB::raw("DATE(created_at)"), $now->format('Y-m-d'))->count();
            $transactionNumber .= str_pad($todaysTransactions + 1, 3, '0', STR_PAD_LEFT);

            $transaction->transaction_number = $transactionNumber;

            // amount source
            $transaction->amount_source = "SELF";
            $transaction->amount = $invoice->total_price;
            $transaction->description = "Invoice payment";
            $transaction->type = 'INVOICE_PAYMENT';
            $transaction->status = 'PENDING';

            $transaction->subject_type = 'INVOICE';
            $transaction->subject_id = $invoice->id;

            $transaction->save();

            perform_transaction($transaction);

            DB::commit();
            toastr()->success('Invoice Marked As Paid');
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();       }
    }

    public function list_receipts()
    {
        $user = Auth::user();
        try {
            $coop_id = $user->cooperative->id;
        } catch (\Throwable $th) {
            $coop_id = null;
        }
        $receipts = DB::table('receipts')
            ->join('customers as cust', 'receipts.customer_id', '=', 'cust.id')
            ->where('cust.cooperative_id', $coop_id) // Use the cooperative_id from the customers table
            ->select('receipts.*', 'cust.name as customer_name', 'cust.email as customer_email') // Add desired customer fields
            ->get();
        return view('pages.cooperative-admin.inventory-auction.receipt', compact("receipts"));
    }

    public function list_sales()
    {
        $now = Carbon::now();
        $saleNumber = "SLE";
        $saleNumber .= $now->format('Ymd');
        $todaysInventories = Sale::where(DB::raw("DATE(created_at)"), $now->format('Y-m-d'))->count();
        $saleNumber .= str_pad($todaysInventories + 1, 3, '0', STR_PAD_LEFT);

        $categories = ProductCategory::all();
        $products = Product::all();
        $sales = Sale::all();
       
        return view('pages.cooperative-admin.inventory-auction.sales.index', compact('sales','categories','products','saleNumber'));
    }

    public function add_sale(Request $request)
    {
      
        $request->validate([
            'product_id' => 'required|string|max:255',
            'quantity' => 'required',
            'amount' => 'required',
            //'batch_number' =>'required|string|max:255',
        ]);
    
       //dd($request->all());

        $user = Auth::user();
        try {
            $coop_id = $user->cooperative->id;
        } catch (\Throwable $th) {
            $coop_id = null;
        }
        
        $exists = Sale::where("cooperative_id", $coop_id)->where("sale_batch_number",$request->sale_batch_number)
                       ->where("published_at", null)->exists();
        if (!$exists) {
            /*
            $now = Carbon::now();
            $saleNumber = "SLE";
            $saleNumber .= $now->format('Ymd'); 
            // count today's inventories
            $todaysInventories = Sale::where(DB::raw("DATE(created_at)"), $now->format('Y-m-d'))->count();
            $saleNumber .= str_pad($todaysInventories + 1, 3, '0', STR_PAD_LEFT);
            */
            DB::beginTransaction();
            // create order
        try {
            $draftSale = new Sale();
            $draftSale->sale_batch_number = $request->sale_batch_number;
            $draftSale->paid_amount = $request->amount;
            $draftSale->miller_id = $coop_id;
            $draftSale->save();
              //sale item
           /* $sale_item = new SaleItem();
            $sale_item->quantity = $request->quantity;
            $sale_item->manufactured_product_id = $request->quantity;
            $sale_item->sales_id  = $request->batch_number;
            $sale_item->amount = $request->amount;
            $sale_item->save();*/
            DB::commit();
            toastr()->success('Sales Updated successfully');
            return redirect()->back();
            //return redirect()->route("cooperative-admin.inventory-auction.list-sales");
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to Save Sale:'.$th->getMessage());
            return redirect()->back()->withInput();
        }


        } else {
            $draftSale = Sale::where("cooperative_id", $coop_id)->where("published_at", null)->firstOrFail();
        }
        return redirect()->route("cooperative-admin.inventory-auction.list-sales");
        //return redirect()->route("cooperative-admin.inventory-auction.view-update-sale", $draftSale->id);
    }

    public function view_update_sale($id)
    {
        $sale = Sale::find($id);
        $categories = ProductCategory::all();

        return view('pages.cooperative-admin.inventory-auction.sales.update-sale', compact('sale','categories'));
    }

    public function view_add_sale()
    {
        $sale = Sale::all();
        $categories = ProductCategory::all();

        return view('pages.cooperative-admin.inventory-auction.sales.add-sale', compact('sale','categories'));
    }

    public function export_quotation($id)
    {
        $quotation = Quotation::find($id);


        $pdf = PDF::loadView('pdf.quotation', compact("quotation"));

        return $pdf->download("quotation_$quotation->quotation_number.pdf");
    }

    public function export_invoice($id)
    {
        $invoice = NewInvoice::find($id);


        $pdf = PDF::loadView('pdf.invoice', compact("invoice"));

        return $pdf->download("invoice_$invoice->invoice_number.pdf");
    }

    public function export_receipt($id)
    {
         $receipt = Receipt::find($id);


        $pdf = PDF::loadView('pdf.receipt', compact("receipt"));

        return $pdf->download("invoice_$receipt->receipt_number.pdf");
    }

    public function export_many_receipts(Request $request, $type)
    {

        $export_status = $request->query("export_status", "all");
        $start_date = $request->query("start_date");
        $end_date = $request->query("end_date");
        $user = Auth::user();
        $coop_id = null;
        $image=null;
        if ($user) {
            $coop_id = $user->cooperative->id;
            $image=$user->profile_picture;
        }
        // $rawQuotations = Quotation::whereNotNull("published_at")->where("cooperative_id", $coop_id)->get();
        $rawReceipt = Receipt::where("created_at", ">=", $start_date)->where("created_at", "<=", $end_date)->get();

        $receipts = [];
        // todo: format data
        foreach ($rawReceipt as $quotation) {

            if ($export_status) {
                if ($status != $export_status && $export_status != 'all') {
                    continue;
                }
            }

            $receipts[] = [
                "receipt_number" => $quotation->receipt_number,
                "customer_name" => $quotation->customer->name,
                "customer_email" => $quotation->customer->email,
                "items_count" => $quotation->items_count,
                "total_price" => $quotation->total_price,
                "status" => $status,
                "created_at" => $quotation->created_at,
            ];
        }


        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('final_products_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new QuotationExport($$receipt), $file_name);
        } else {
            $columns = [
                ['name' => 'Receipt Number', 'key' => "receipt_number"],
                ['name' => 'Customer Name', 'key' => "customer_name"],
                ['name' => 'Customer Email', 'key' => "customer_email"],
                ['name' => 'Items Count', 'key' => "items_count"],
                ['name' => 'Total Price', 'key' => "total_price"],
                ['name' => 'Status', 'key' => "status"],
                ['name' => 'Created At', 'key' => "created_at"],
            ];

            $imagePath = public_path('storage/' . $image); // Absolute path to image

            $data = [
                'title' => 'Receipts',
                'pdf_view' => 'receipts',
                'records' =>  $receipts,
                'image'=>$imagePath,
                'filename' => strtolower('receipts_' . date('d_m_Y')),
                'orientation' => 'letter',
            ];
            return download_pdf($columns, $data);
        }
    }


    public function export_sales($type)
    {
        $user = Auth::user();
        $coop_id = null;
        if ($user) {
            $coop_id = $user->cooperative->id;
            $image=$user->profile_picture;
        }
        $sales = collect(Sale::where("cooperative_id", $coop_id)->get());

        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('sales' . date('d_m_Y')) . '.' . $type;
            $sales = collect(Sale::where("cooperative_id", $coop_id)->get());
           // dd($sales);
            return Excel::download(new SaleExport($sales), $file_name);
        } else {
            $columns = [
                ['name' => 'Batch No', 'key' => "sale_batch_number"],
                ['name' => 'Sale Amount', 'key' => "paid_amount"],
            ];
            $imagePath = public_path('storage/' . $image); // Absolute path to image
            $data = [
                'title' => 'Sales',
                'pdf_view' => 'sales',
                'records' => $sales,
                'filename' => strtolower('sales_' . date('d_m_Y')),
                'orientation' => 'letter',
                'image'=>$imagePath,
            ];
            return download_pdf($columns, $data);
        }
    }

    public function export_customers($type)
    {
        $user = Auth::user();
        $coop_id = null;
        if ($user) {
            $coop_id = $user->cooperative->id;
        }
        $image=$user->profile_picture;
        $customers = Customer::where("cooperative_id", $coop_id)->whereNotNull("published_at")->get();
         //dd($customers);
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('customers' . date('d_m_Y')) . '.' . $type;
            $$customers = collect(Customer::where("cooperative_id", $coop_id)->whereNotNull("published_at")->get());
           // dd($sales);
            return Excel::download(new CustomersExport($customers), $file_name);
        } else {
            $columns = [
                ['name' => 'Title', 'key' => "title"],
                ['name' => 'Name', 'key' => "name"],
                ['name' => 'Gender', 'key' => "gender"],
                ['name' => 'Email', 'key' => "email"],
                ['name' => 'Phone No', 'key' => "phone_number"],
                ['name' => 'Address', 'key' => "address"],
                //['name' => 'Location', 'key' => "location"],
            ];
            $imagePath = public_path('storage/' . $image); // Absolute path to image
            $data = [
                'title' => 'Customers',
                'pdf_view' => 'customer',
                'records' => $customers,
                'filename' => strtolower('customers_' . date('d_m_Y')),
                'orientation' => 'letter',
                'image'=>$imagePath,
            ];
            return download_pdf($columns, $data);
        }
    }


    




    }




