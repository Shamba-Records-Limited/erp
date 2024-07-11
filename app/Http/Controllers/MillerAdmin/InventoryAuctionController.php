<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Log;

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
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $customers = Customer::where("miller_id", $miller_id)->whereNotNull("published_at")->get();

        return view('pages.miller-admin.inventory-auction.customers.index', compact('customers'));
    }

    public function add_customer()
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $exists = Customer::where("miller_id", $miller_id)->where("published_at", null)->exists();
        if (!$exists) {
            $draftCustomer = new Customer();
            $draftCustomer->miller_id = $miller_id;
            $draftCustomer->save();
        }

        $draftCustomer = Customer::where("miller_id", $miller_id)->where("published_at", null)->firstOrFail();

        return redirect()->route("miller-admin.inventory-auction.view-update-customer-details", $draftCustomer->id);
    }

    public function view_update_customer_details($id)
    {
        $customer = Customer::find($id);

        return view('pages.miller-admin.inventory-auction.customers.update-customer-details', compact('customer'));
    }

    public function update_customer_details(Request $request)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $request->validate([
            "customer_id" => "required|exists:customers,id",
            "title" => "required",
            "name" => "required",
            "gender" => "required",
            "email" => ["required","email", Rule::unique('customers')->where(function ($query) use ($miller_id, $request) {
                return $query->where("email", $request->email)->where("miller_id", $miller_id);
            })->ignore($request->customer_id)],
            "phone_number" => ["required", Rule::unique('customers')->where(function ($query) use ($miller_id, $request) {
                return $query->where("phone_number", $request->phone_number)->where("miller_id", $miller_id);
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
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function view_customer($id, Request $request)
    {
        $customer = Customer::find($id);

        $tab = $request->query("tab", "sales");

        return view('pages.miller-admin.inventory-auction.customers.detail', compact('customer', 'tab'));
    }

    public function list_quotations()
    {

    }

    public function list_sales()
    {
        $sales = [];
        return view('pages.miller-admin.inventory-auction.sales', compact('sales', 'tab'));
    }
}
