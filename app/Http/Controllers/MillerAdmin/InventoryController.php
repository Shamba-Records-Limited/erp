<?php

namespace App\Http\Controllers\MillerAdmin;

use App\AuctionOrderDeliveryItem;
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

    public function pre_milled(Request $request){
        $isMilling = $request->query('is_milling', '0');
        $inventoryId = $request->query('inventory_id', null);

        $user_id = Auth::id();
        
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $preMilledInventory = DB::select(DB::raw("
            SELECT item.*, a_order.batch_number, order_item.lot_number as l_num
            FROM auction_order_delivery_item item
            JOIN miller_auction_order_item order_item ON order_item.id = item.order_item_id
            JOIN miller_auction_order a_order ON a_order.id = order_item.order_id
            WHERE a_order.miller_id = :miller_id AND item.milled_quantity IS NULL
        "),["miller_id" => $miller_id]);

        $inventory = null;
        if ($isMilling == '1' && $inventoryId != null) {
            $inventories = DB::select(DB::raw("
                SELECT item.*
                FROM auction_order_delivery_item item
                WHERE item.id = :inventory_id
            "), ["inventory_id" => $inventoryId]);
            if (count($inventories) > 0) {
                $inventory = $inventories[0];
            }
        }

        return view('pages.miller-admin.inventory.pre_milled', compact('preMilledInventory', 'isMilling', 'inventory'));
    }
    

    public function milled(){
        $user_id = Auth::id();
        
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $milledInventory = DB::select(DB::raw("
            SELECT item.*, a_order.batch_number, order_item.lot_number as l_num
            FROM auction_order_delivery_item item
            JOIN miller_auction_order_item order_item ON order_item.id = item.order_item_id
            JOIN miller_auction_order a_order ON a_order.id = order_item.order_id
            WHERE a_order.miller_id = :miller_id AND item.milled_quantity IS NOT NULL
        "),["miller_id" => $miller_id]);


        return view('pages.miller-admin.inventory.milled', compact('milledInventory'));
    }

    public function save_milling(Request $request){
        $request->validate([
            "inventory_id" => "required|exists:auction_order_delivery_item,id",
            "milled_quantity" => "required|numeric",
            "waste_quantity" => "required|numeric",
        ]);

        $inventory_id = $request->inventory_id;
        $milled_quantity = $request->milled_quantity;
        $waste_quantity = $request->waste_quantity;
        
        DB::beginTransaction();

        try {
            $inventory = AuctionOrderDeliveryItem::find($inventory_id);

            // throw error if output + waste != inventory.quantity
            $total = floatval($milled_quantity) + floatval($waste_quantity);
            if ($total != floatval($inventory->quantity)) {
                throw new \Exception("Milled + Waste must be equal to inventory quantity");
            }

            $inventory->milled_quantity = $milled_quantity;
            $inventory->waste_quantity = $waste_quantity;
            $inventory->save();

            DB::commit();
            toastr()->success("Inventory updated successfully");
            return redirect()->route("miller-admin.pre-milled-inventory.show");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }

    }

    public function final_products()
    {
        $user_id = Auth::id();
        
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }
        
        
    }

    public function index(Request $request)
    {
        $user_id = Auth::id();
        
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

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

                $draftInventory = new Inventory();
                $draftInventory->inventory_number = $inventoryNumber;
                $draftInventory->user_id = $user_id;
                $draftInventory->miller_id = $miller_id;
                $draftInventory->save();
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

    public function save(Request $request)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            throw $th;
        }

        $user_id = Auth::id();

        $request->validate([
            'inventory_number' => 'required|exists:inventories,inventory_number',
            'order_id' => 'required|exists:miller_auction_order,id',
        ]);

        DB::beginTransaction();
        try {
            $inventory = Inventory::where('inventory_number', $request->inventory_number)->first();
            $inventory->order_id = $request->order_id;
            $inventory->save();

            toastr()->success('Inventory updated successfully.');
            DB::commit();
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to save inventory');
            return redirect()->back();
        }
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

    public function publish($inventory_number)
    {
        $inventory = null;
        
        try {
            $inventory = Inventory::where('inventory_number', $inventory_number)->first();
            if (!$inventory) {
                toastr()->error('Inventory not found.');
                return redirect()->back();
            }

            $inventory->published_at = Carbon::now();
            $inventory->save();

            toastr()->success('Inventory published successfully.');
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th->getMessage());
            toastr()->error('Unable to publish inventory');
            return redirect()->back();
        }
    }
}
