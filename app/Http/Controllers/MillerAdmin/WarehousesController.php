<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehousesController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $warehouses = [];
        $user = Auth::user();
        $miller_id = null;
        if ($user->miller_admin) {
            $miller_id = $user->miller_admin->miller_id;
        }


        $warehouses = DB::select(DB::raw("
                SELECT
                    w.*
                FROM miller_warehouse w
                WHERE w.miller_id = :miller_id
            "), ["miller_id" => $miller_id]);


        return view('pages.miller-admin.warehouses.index', compact('user','warehouses'));
    }
}
