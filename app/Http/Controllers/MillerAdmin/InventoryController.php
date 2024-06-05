<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Http\Controllers\Controller;
use App\Inventory;
use App\InventoryItem;
use App\ProductGrade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;

class InventoryController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }


    public function index(Request $request)
    {
        $user_id = Auth::id();
        $is_adding_inventory = $request->query('is_adding_inventory', '0');

        $inventories = Inventory::all();

        $selectableOrderItems = [];
        $draftInventory = null;
        $inventoryNumber = null;
        $grades = [];
        $draftInventoryItems = [];
        if ($is_adding_inventory == '1') {

            $draftInventories = DB::select(DB::raw("
                SELECT id, inventory_number, order_id
                FROM inventories
                WHERE user_id = :user_id
                AND published_at IS NULL;
            "), ["user_id" => $user_id]);

            if (count($draftInventories) > 0) {
                $draftInventory = $draftInventories[0];
                $inventoryNumber = $draftInventory->inventory_number;

                $inventory_id = $draftInventory->id;

                $draftInventoryItems = DB::select(DB::raw("
                    SELECT item.id, item.name, item.quantity, item.unit, pg.name as product_grade FROM inventory_items item
                    LEFT JOIN product_grades pg ON pg.id = item.product_grade_id
                    WHERE item.inventory_id = '$inventory_id'
                "));
            } else {
                $now = Carbon::now();
                $inventoryNumber = "INV";
                $inventoryNumber .= $now->format('Ymd');

                // count today's inventories
                $todaysInventories = Inventory::where(DB::raw("DATE(created_at)"), $now->format('Y-m-d'))->count();
                $inventoryNumber .= str_pad($todaysInventories + 1, 3, '0', STR_PAD_LEFT);
            }


            // todo: load selectable order items from database
            $selectableOrderItems = DB::select(DB::raw("
                SELECT id, batch_number
                FROM miller_auction_order;
            "));

            $grades = ProductGrade::all();
        }


        return view('pages.miller-admin.inventory.index', compact('inventories', 'is_adding_inventory', 'draftInventory', 'draftInventoryItems', 'selectableOrderItems', 'inventoryNumber', 'grades'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            throw $th;
        }

        $user_id = Auth::id();

        $request->validate([
            'inventory_number' => 'required|unique:inventories,inventory_number',
            'order_id' => 'required|exists:miller_auction_order,id',
        ]);

        DB::beginTransaction();
        try {
            $inventory = new Inventory();
            $inventory->inventory_number = $request->inventory_number;
            $inventory->miller_id = $miller_id;
            $inventory->order_id = $request->order_id;
            $inventory->user_id = $user_id;
            $inventory->save();

            DB::commit();
            toastr()->success('Inventory created successfully.');
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to save inventory');
            return redirect()->back();
        }
    }

    public function add_item(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'name' => 'required',
            'quantity' => 'required|numeric',
            'unit' => 'required',
            'product_grade_id' => 'required',
        ]);

        try {
            $inventoryItem = new InventoryItem();
            $inventoryItem->inventory_id = $request->inventory_id;
            $inventoryItem->name = $request->name;
            $inventoryItem->quantity = $request->quantity;
            $inventoryItem->unit = $request->unit;
            $inventoryItem->product_grade_id = $request->product_grade_id;
            $inventoryItem->save();

            toastr()->success('Inventory item added successfully.');
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to save inventory item');
            return redirect()->back();
        }


    }
}
