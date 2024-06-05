<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryAuctionController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request){
        $is_adding_sale = $request->query('is_adding_sale', '0');

        $inventoryItems = DB::select(DB::raw("
            SELECT item.*, inventory.inventory_number FROM inventory_items item
            JOIN inventories inventory ON inventory.id = item.inventory_id
        "));


        return view('pages.miller-admin.inventory-auction.index', compact('inventoryItems', 'is_adding_sale'));
    }

    public function add_sale(Request $request)
    {
        return redirect()->back();
    }
}

