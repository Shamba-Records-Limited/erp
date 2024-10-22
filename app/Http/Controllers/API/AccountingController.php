<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function pending_payments(): \Illuminate\Http\JsonResponse
    {
        $cooperative = Auth::user()->cooperative->id;
        $pending_payments = DB::select("
            SELECT u.id as id, w.current_balance, c.currency, u.first_name, u.other_names, f.phone_no FROM wallets w 
            JOIN farmers f ON f.id = w.farmer_id
            JOIN  users u ON f.user_id  = u.id
            JOIN cooperatives c on u.cooperative_id = c.id                                                                              
            WHERE u.cooperative_id = '$cooperative';
        ");

        return response()->json([
            "success" => true,
            "message" => "Success",
            "data" => $pending_payments
        ]);
    }

}
