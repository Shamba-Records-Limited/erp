<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Events\AuditTrailEvent;
use App\Exports\CustomersExport;
use App\Mail\CustomerAlertMail;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;

class SupplierController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }


    public function index()
    {
        $suppliers = Supplier::where('cooperative_id', Auth::user()->cooperative->id)
            ->latest()->limit(100)->get();
        return view('pages.cooperative.manufacturing.supplier.index', compact('suppliers'));
    }

    public function getSuppliers()
    {
        $coop = Auth::user()->cooperative->id;

        return Supplier::where('cooperative_id', $coop)->latest()->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'phone_no' => 'required|regex:/^[0-9]{10}$/',
            'name' => 'required|string',
            'email' => 'required|email',
            'supplier_type' => 'required',
            'gender' => 'required_if:supplier_type,==,1',
            'title' => 'required_if:supplier_type,==,1',
        ]);


        try {
            $user = Auth::user();
            DB::beginTransaction();
            //audit trail log
            $this->persist($request, new Supplier(), $user);
            //send email and new audit trail
            $data = ["name" => ucwords(strtolower($request->title)) . ' ' . ucwords(strtolower($request->name))];
            Mail::to($request->email)->send(new CustomerAlertMail($data));
            $customer_creation_log = ['user_id' => $user->id,
                'activity' => 'Add new supplier: ' . $request->name,
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($customer_creation_log));
            DB::commit();
            toastr()->success('Supplier Added Successfully');
            return redirect()->route('cooperative.suppliers');

        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
            toastr()->error('Oops! Error occurred');
            return redirect()->back()->withInput();
        }

    }


    private function persist(Request $req, Supplier $customer, User $user)
    {
        $customer->name = $req->name;
        $customer->title = $req->supplier_type == Customer::CUSTOMER_TYPE_INDIVIDUAL ? $req->title : null;
        $customer->gender = $req->supplier_type == Customer::CUSTOMER_TYPE_INDIVIDUAL ? $req->gender : null;
        $customer->email = $req->email;
        $customer->supplier_type = $req->supplier_type;
        $customer->phone_number = $req->phone_no;
        $customer->cooperative_id = $user->cooperative->id;
        $customer->location = $req->location;
        $customer->address = $req->address;
        $customer->save();
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export_suppliers($type)
    {
        $cooperative = Auth::user()->cooperative->id;
        $suppliers = Supplier::where('cooperative_id', $cooperative)->orderBy('created_at', 'DESC')->get();
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('suppliers_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new CustomersExport($suppliers), $file_name);
        } else {
            $data = [
                'title' => 'Suppliers',
                'pdf_view' => 'customers',
                'records' => $suppliers,
                'filename' => strtolower('customers_' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return deprecated_download_pdf($data);
        }
    }
}
