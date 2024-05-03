<?php

namespace App\Http\Controllers\API;

use App\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function get_customers()
    {

        $customers = Customer::select('id', 'name', 'title', 'gender', 'email', 'phone_number', 'last_visit')
            ->where('cooperative_id', Auth::user()->cooperative->id)->orderBy('last_visit')->get();
        return response()->json([
            "success" => true,
            "message" => "Success",
            "data" => $customers
        ]);
    }
}
