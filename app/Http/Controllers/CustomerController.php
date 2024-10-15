<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Events\AuditTrailEvent;
use App\Exports\CustomersExport;
use App\Mail\CustomerAlertMail;
use App\User;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }


    public function index()
    {
        $customers = Customer::where('cooperative_id', Auth::user()->cooperative->id)
            ->latest()->limit(100)->get();
        return view('pages.cooperative.customer.index', compact('customers'));
    }

    public function getCustomers()
    {
        $coop = Auth::user()->cooperative->id;

        $customers = Customer::where('cooperative_id', $coop)->latest()->get();
        return $customers;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'phone_no' => 'required|regex:/^[0-9]{10}$/',
            'name' => 'required|string',
            'email' => 'required|email',
            'customer_type' => 'required',
            'gender' => 'required_if:customer_type,==,1',
            'title' => 'required_if:customer_type,==,1',
        ]);

        try {
            $user = Auth::user();
            DB::beginTransaction();
            //audit trail log
            $customer_creation_log = ['user_id' => $user->id, 'activity' => 'Add new customer ',
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($customer_creation_log));
            $this->persist($request, new Customer(), $user);
            //send email and new audit trail
            $data = ["name" => ucwords(strtolower($request->title)) . ' ' . ucwords(strtolower($request->name))];
            Mail::to($request->email)->send(new CustomerAlertMail($data));
            DB::commit();
            toastr()->success('Customer Created Successfully');
            return redirect()->route('cooperative.customers');

        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
            toastr()->success('Oops! Error occurred');
            return redirect()->back();
        }

    }


    private function persist(Request $req, Customer $customer, User $user)
    {
        try {
            DB::beginTransaction();
            $customer->name = $req->name;
            $customer->title = $req->customer_type == Customer::CUSTOMER_TYPE_INDIVIDUAL ? $req->title : null;
            $customer->gender = $req->customer_type == Customer::CUSTOMER_TYPE_INDIVIDUAL ? $req->gender : null;
            $customer->email = $req->email;
            $customer->customer_type = $req->customer_type;
            $customer->phone_number = $req->phone_no;
            $customer->cooperative_id = $user->cooperative->id;
            $customer->last_visit = Carbon::now();
            $customer->location = $req->location;
            $customer->address = $req->address;
            $customer->save();
            DB::commit();

        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
        }

    }

    public function export_customers($type)
    {
        $cooperative = Auth::user()->cooperative->id;
        $customers  = Customer::where('cooperative_id', $cooperative)->orderBy('created_at', 'DESC')->get();

        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('customers_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new CustomersExport($customers), $file_name);
        } else {
            $data = [
                'title' => 'Customers',
                'pdf_view' => 'customers',
                'records' => $customers,
                'filename' => strtolower('customers_' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return deprecated_download_pdf($data);
        }
    }
}
