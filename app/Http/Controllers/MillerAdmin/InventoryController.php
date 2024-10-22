<?php

namespace App\Http\Controllers\MillerAdmin;

use App\AuctionOrderDeliveryItem;
use App\Exports\FinalProductExport;
use App\Exports\MilledInventoryExport;
use App\Exports\PreMilledInventoryExport;
use App\FinalProduct;
use App\FinalProductRawMaterial;
use App\Http\Controllers\Controller;
use App\Inventory;
use App\InventoryItem;
use App\MilledInventory;
use App\MilledInventoryGrade;
use App\PreMilledInventory;
use App\ProductGrade;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Log;

class InventoryController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function pre_milled(Request $request)
    {
        $isMilling = $request->query('is_milling', '0');
        $preMilledInventoryId = $request->query('pre_milled_inventory_id', null);

        $user_id = Auth::id();

        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $preMilledInventories = DB::select(DB::raw("
            SELECT inv.*, delivery_item.quantity, a_order.batch_number, order_item.lot_number as l_num
            FROM pre_milled_inventories inv
            JOIN auction_order_delivery_item delivery_item ON delivery_item.id = inv.delivery_item_id
            JOIN miller_auction_order_item order_item ON order_item.id = delivery_item.order_item_id
            JOIN miller_auction_order a_order ON a_order.id = order_item.order_id
            WHERE a_order.miller_id = :miller_id
        "), ["miller_id" => $miller_id]);

        $millingQty = 0;
        if ($isMilling == "1") {
            if ($preMilledInventoryId) {
                $preMilledInventory = PreMilledInventory::find($preMilledInventoryId);
                $millingQty = $preMilledInventory->quantity;
            }
        }


        return view('pages.miller-admin.inventory.pre_milled', compact('preMilledInventories', 'isMilling', 'preMilledInventoryId', 'millingQty'));
    }

    public function export_pre_milled_inventories($type)
    {
        $user = Auth::user();
        $miller_id = null;
        if ($user->miller_admin) {
            $miller_id = $user->miller_admin->miller_id;
        }

        $preMilledInventories = DB::select(DB::raw("
            SELECT inv.*, delivery_item.quantity, a_order.batch_number, order_item.lot_number as l_num
            FROM pre_milled_inventories inv
            JOIN auction_order_delivery_item delivery_item ON delivery_item.id = inv.delivery_item_id
            JOIN miller_auction_order_item order_item ON order_item.id = delivery_item.order_item_id
            JOIN miller_auction_order a_order ON a_order.id = order_item.order_id
            WHERE a_order.miller_id = :miller_id
        "), ["miller_id" => $miller_id]);


        // if ($request->request_data == '[]') {
        //     $request = null;
        // } else {
        //     $request = json_decode($request->request_data);
        // }

        $inventories = [];
        // todo: format data
        foreach ($preMilledInventories as $inventory) {

            $inventories[] = [
                "inventory_number" => $inventory->inventory_number,
                "batch_number" => $inventory->batch_number,
                "lot_number" => $inventory->l_num,
                "quantity" => $inventory->quantity . " KGs",
            ];
        }


        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('orders_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new PreMilledInventoryExport($inventories), $file_name);
        } else {
            $columns = [
                ['name' => 'Inventory No', 'key' => "inventory_number"],
                ['name' => 'Batch No', 'key' => "batch_number"], // to generate
                ['name' => 'Lot No', 'key' => "lot_number"], // to generate
                ['name' => 'Quantity', 'key' => "quantity"],
            ];

            $data = [
                'title' => 'Pre Milled Inventories',
                'pdf_view' => 'pre_milled_inventories',
                'records' => $inventories,
                'filename' => strtolower('pre_milled_inventories_' . date('d_m_Y')),
                'orientation' => 'letter',
            ];
            return download_pdf($columns, $data);
        }
    }


    public function milled(Request $request)
    {
        $user_id = Auth::id();

        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }


        $milledInventories = DB::select(DB::raw("
            SELECT inv.*, a_order.batch_number, order_item.lot_number as l_num
            FROM milled_inventories inv
            JOIN pre_milled_inventories pre_inv ON pre_inv.id = inv.pre_milled_inventory_id
            JOIN auction_order_delivery_item delivery_item ON delivery_item.id = pre_inv.delivery_item_id
            JOIN miller_auction_order_item order_item ON order_item.id = delivery_item.order_item_id
            JOIN miller_auction_order a_order ON a_order.id = order_item.order_id
            WHERE inv.miller_id = :miller_id
        "), ["miller_id" => $miller_id]);

        $isGrading = $request->query("is_grading", "0");

        $gradings = [];
        if ($isGrading == "1") {
        }



        return view('pages.miller-admin.inventory.milled.index', compact('milledInventories', 'isGrading', 'gradings'));
    }

    public function export_milled_inventories($type)
    {
        $user = Auth::user();
        $miller_id = null;
        if ($user->miller_admin) {
            $miller_id = $user->miller_admin->miller_id;
        }

        $milledInventories = DB::select(DB::raw("
            SELECT inv.*, a_order.batch_number, order_item.lot_number as l_num
            FROM milled_inventories inv
            JOIN pre_milled_inventories pre_inv ON pre_inv.id = inv.pre_milled_inventory_id
            JOIN auction_order_delivery_item delivery_item ON delivery_item.id = pre_inv.delivery_item_id
            JOIN miller_auction_order_item order_item ON order_item.id = delivery_item.order_item_id
            JOIN miller_auction_order a_order ON a_order.id = order_item.order_id
            WHERE inv.miller_id = :miller_id
        "), ["miller_id" => $miller_id]);


        // if ($request->request_data == '[]') {
        //     $request = null;
        // } else {
        //     $request = json_decode($request->request_data);
        // }

        $inventories = [];
        // todo: format data
        foreach ($milledInventories as $inventory) {

            $quantity = $inventory->milled_quantity + $inventory->waste_quantity;

            $inventories[] = [
                "batch_number" => $inventory->batch_number,
                "lot_number" => $inventory->l_num,
                "quantity" => $quantity . " KGs",
                "milled_quantity" => $inventory->milled_quantity . " KGs",
                "waste_quantity" => $inventory->waste_quantity . " KGs",
            ];
        }


        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('orders_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new MilledInventoryExport($inventories), $file_name);
        } else {
            $columns = [
                ['name' => 'Batch No', 'key' => "batch_number"],
                ['name' => 'Lot No', 'key' => "lot_number"],
                ['name' => 'Quantity', 'key' => "quantity"],
                ['name' => 'Milled Quantity', 'key' => "milled_quantity"],
                ['name' => 'Waste Quantity', 'key' => "waste_quantity"],
            ];

            $data = [
                'title' => 'Milled Inventories',
                'pdf_view' => 'milled_inventories',
                'records' => $inventories,
                'filename' => strtolower('milled_inventories_' . date('d_m_Y')),
                'orientation' => 'letter',
            ];
            return download_pdf($columns, $data);
        }
    }

    public function milled_details(Request $request, $id)
    {
        $milling = MilledInventory::find($id);

        $gradings = MilledInventoryGrade::with("product_grade")->get();

        # todo: fetch actual lot unit
        $lot_unit = "KG";

        $isAddingGrade = $request->query("is_adding_grade", "0");
        $grades = [];
        if ($isAddingGrade == "1") {
            $grades = DB::select(DB::raw("
                SELECT g.* FROM product_grades g;
            "));
        }

        return view('pages.miller-admin.inventory.milled.detail', compact('id', 'milling', 'gradings', 'lot_unit', "isAddingGrade", "grades"));
    }

    public function save_milling(Request $request)
    {
        $request->validate([
            "pre_milled_inventory_id" => "required|exists:pre_milled_inventories,id",
            "milled_quantity" => "required|numeric",
            "waste_quantity" => "required|numeric",
        ]);

        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }



        $milled_quantity = $request->milled_quantity;
        $waste_quantity = $request->waste_quantity;

        DB::beginTransaction();

        try {
            $now = Carbon::now();
            $inventoryNumber = "INV";
            $inventoryNumber .= $now->format('Ymd');

            // count today's inventories
            $todaysInventories = MilledInventory::where(DB::raw("DATE(created_at)"), $now->format('Y-m-d'))->count();
            $inventoryNumber .= str_pad($todaysInventories + 1, 3, '0', STR_PAD_LEFT);

            $inventory = new MilledInventory();
            $inventory->inventory_number = $inventoryNumber;
            $inventory->pre_milled_inventory_id = $request->pre_milled_inventory_id;
            $inventory->milled_quantity = $milled_quantity;
            $inventory->waste_quantity = $waste_quantity;
            $inventory->miller_id = $miller_id;
            $inventory->user_id = Auth::id();
            $inventory->save();

            // update pre milled inventory
            $preMilledInventory = PreMilledInventory::find($request->pre_milled_inventory_id);
            $preMilledInventory->milled_inventory_id = $inventory->id;
            $preMilledInventory->save();

            DB::commit();
            toastr()->success("Inventory milled successfully");
            return redirect()->route("miller-admin.pre-milled-inventory.show");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function store_milled_inventory_grade(Request $request)
    {
        $request->validate([
            "milled_inventory_id" => "required|exists:milled_inventories,id",
            "product_grade_id" => "required|exists:product_grades,id",
            "quantity" => "required|numeric",
            "unit" => "required",
        ]);

        DB::beginTransaction();
        try {
            $grade = new MilledInventoryGrade();
            $grade->milled_inventory_id = $request->milled_inventory_id;
            $grade->product_grade_id = $request->product_grade_id;
            $grade->quantity = $request->quantity;
            $grade->unit = $request->unit;
            $grade->save();


            DB::commit();
            toastr()->success("Grade added successfully");
            return redirect()->route("miller-admin.milled-inventory.detail", $request->milled_inventory_id);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function delete_milled_inventory_grade($id)
    {
        DB::beginTransaction();
        try {
            $grade = MilledInventoryGrade::find($id);
            $grade->delete();

            DB::commit();
            toastr()->success("Grade deleted successfully");
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function final_products(Request $request)
    {
        $user_id = Auth::id();

        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $finalProducts = FinalProduct::whereNotNull("published_at")->get();

        $isCreatingFinalProduct = $request->query("is_creating_final_product", "0");
        $uniqueProductNames =  [];

        $curStep = $request->query("cur_step", "1");

        $draftProduct = null;
        $rawMaterials = [];
        $milledInventories = [];
        if ($isCreatingFinalProduct == '1') {
            $exists = FinalProduct::where('miller_id', $miller_id)->where('user_id', $user_id)->where('published_at', null)->exists();

            if ($exists == false) {
                try {
                    // generate product number
                    $now = Carbon::now();
                    $now_str = strtoupper($now->format('Ymd'));
                    $date_str = $now->format('Y-m-d') . " 00:00:00";
                    $dateAfter_str = $now->format('Y-m-d') . " 23:59:59";

                    $product_count = FinalProduct::where('created_at', '>=', $date_str)
                        ->where('created_at', '<', $dateAfter_str)
                        ->count();

                    $product_ind = $product_count + 1;

                    $productNumber = "PRD" . $now_str . str_pad($product_ind, 3, '0', STR_PAD_LEFT);



                    $newDraftProduct = new FinalProduct();
                    $newDraftProduct->product_number = $productNumber;
                    $newDraftProduct->miller_id = $miller_id;
                    $newDraftProduct->user_id = $user_id;
                    $newDraftProduct->is_wholesale = 0;
                    $newDraftProduct->is_retail = 0;
                    $newDraftProduct->save();
                } catch (\Throwable $th) {
                    throw $th;
                }
            }

            $draftProductsList = DB::select(DB::raw("
                SELECT fp.* FROM final_products fp
                WHERE miller_id = :miller_id
                AND user_id = :user_id
                AND published_at IS NULL
                ORDER BY fp.created_at DESC
                LIMIT 1
            "), ["miller_id" => $miller_id, "user_id" => $user_id]);

            if (count($draftProductsList) > 0) {
                $draftProduct = $draftProductsList[0];
            } else {
                toastr()->error("No draft product found");
                return redirect()->back();
            }

            $uniqueProductNames = Cache::remember("product_names_m_$miller_id", 60, function () use ($miller_id) {
                return DB::select(DB::raw("
                SELECT fp.name FROM final_products fp
                WHERE miller_id = :miller_id
            "), ["miller_id" => $miller_id]);
            });

            if ($curStep == "2") {
                // load raw materials
                $rawMaterials = FinalProductRawMaterial::with("milled_inventory")->where("final_product_id", $draftProduct->id)->get();

                $milledInventories = MilledInventory::all();
            }
        }


        return view("pages.miller-admin.inventory.final_products.index", compact("finalProducts", "isCreatingFinalProduct", "uniqueProductNames", "draftProduct", "curStep", "rawMaterials", "milledInventories"));
    }

    public function export_final_products($type)
    {
        $user = Auth::user();
        $miller_id = null;
        if ($user->miller_admin) {
            $miller_id = $user->miller_admin->miller_id;
        }

        $rawFinalProducts = FinalProduct::whereNotNull("published_at")->where("miller_id", $miller_id)->get();

        $finalProducts = [];
        // todo: format data
        foreach ($rawFinalProducts as $finalProduct) {

            $finalProducts[] = [
                "product_number" => $finalProduct->product_number,
                "name" => $finalProduct->name,
                "quantity" => $finalProduct->quantity . " " . $finalProduct->unit,
                "selling_price" => $finalProduct->selling_price,
                "count" => $finalProduct->count,
            ];
        }

        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('final_products_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new FinalProductExport($finalProducts), $file_name);
        } else {
            $columns = [
                ['name' => 'Product Number', 'key' => "product_number"],
                ['name' => 'Product', 'key' => "name"],
                ['name' => 'Quantity', 'key' => "quantity"],
                ['name' => 'Pricing', 'key' => "selling_price"],
                ['name' => 'Count', 'key' => "count"],
            ];
            $data = [
                'title' => 'Final Products',
                'pdf_view' => 'final_products',
                'records' => $finalProducts,
                'filename' => strtolower('final_products_' . date('d_m_Y')),
                'orientation' => 'letter',
            ];
            return download_pdf($columns, $data);
        }
    }

    public function save_final_product_details(Request $request)
    {
        $request->validate([
            "product_id" => "required|exists:final_products,id",
            "name" => "required",
            "quantity" => "required|numeric",
            "unit" => "required",
            "selling_price" => "required|numeric",
            "count" => "required|numeric",
        ]);

        DB::beginTransaction();

        try {
            $finalProduct = FinalProduct::find($request->product_id);

            $finalProduct->name = $request->name;
            $finalProduct->quantity = $request->quantity;
            $finalProduct->unit = $request->unit;
            $finalProduct->selling_price = $request->selling_price;
            $finalProduct->count = $request->count;
            $finalProduct->is_wholesale = $request->is_wholesale == "on";
            $finalProduct->is_retail = $request->is_retail == "on";
            $finalProduct->save();

            DB::commit();
            toastr()->success("Final product created successfully");
            return redirect()->route("miller-admin.final-products.show", ["is_creating_final_product" => 1, "cur_step" => 2]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function save_final_product_raw_material(Request $request)
    {
        $request->validate([
            "product_id" => "required|exists:final_products,id",
            "milled_inventory_id" => "required|exists:milled_inventories,id",
            "quantity" => "required|numeric",
            "unit" => "required",
        ]);

        DB::beginTransaction();
        try {
            $rawMaterial = new FinalProductRawMaterial();
            $rawMaterial->final_product_id = $request->product_id;
            $rawMaterial->milled_inventory_id = $request->milled_inventory_id;
            $rawMaterial->quantity = $request->quantity;
            $rawMaterial->unit = $request->unit;
            $rawMaterial->save();


            DB::commit();
            toastr()->success("Final product raw material recorded successfully");
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function delete_final_product_raw_material($id)
    {
        DB::beginTransaction();
        try {
            $rawMaterial = FinalProductRawMaterial::find($id);
            $rawMaterial->delete();


            DB::commit();
            toastr()->success("Final product raw material deleted successfully");
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function discard_final_product_draft(Request $request)
    {
        $user_id = Auth::id();

        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        DB::beginTransaction();
        try {
            $productDraft = FinalProduct::where('miller_id', $miller_id)
                ->where('user_id', $user_id)
                ->where('published_at', null)
                ->first();

            // delete product raw materials
            FinalProductRawMaterial::where("final_product_id", $productDraft->id)->delete();

            $productDraft->delete();

            DB::commit();
            toastr()->success("Final product draft discarded");
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function publish_final_product()
    {
        $user_id = Auth::id();

        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        try {
            $finalProduct = FinalProduct::where('miller_id', $miller_id)
                ->where('user_id', $user_id)
                ->where('published_at', null)
                ->firstOrFail();

            if (is_null($finalProduct->name) || $finalProduct->name == "") {
                toastr()->error("Save product details first");
                return redirect()->back();
            }

            $finalProduct->published_at = Carbon::now();
            $finalProduct->save();

            toastr()->success("Product published successfully");
            return redirect()->route("miller-admin.final-products.show");
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
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
