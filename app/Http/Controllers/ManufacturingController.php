<?php

namespace App\Http\Controllers;

use App\Category;
use App\Collection;
use App\Events\AuditTrailEvent;
use App\Exports\ExpiredStockExport;
use App\Exports\ManufacturingProductionExport;
use App\Exports\ManufacturingProductsExport;
use App\Exports\ManufacturingRawMaterialsExport;
use App\Exports\ManufacturingReportExport;
use App\Exports\ManufacturingSupplyDetailsExport;
use App\Exports\ProductionHistoryExport;
use App\FinalProduct;
use App\ManufacturingStore;
use App\Product;
use App\Production;
use App\ProductionHistory;
use App\ProductionMaterial;
use App\ProductionStockTracker;
use App\RawMaterial;
use App\RawMaterialSupplyHistory;
use App\RawMaterialSupplyPayment;
use App\Supplier;
use App\Unit;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;
use Log;

class ManufacturingController extends Controller
{
    public function index()
    {
        $coop = Auth::user()->cooperative->id;
        $products = FinalProduct::where('cooperative_id', $coop)->latest()->get();
        $categories = Category::where('cooperative_id', $coop)->latest()->get();
        $units = Unit::where('cooperative_id', $coop)->latest()->get();
        return view('pages.cooperative.manufacturing.product.index', compact('products', 'units', 'categories'));
    }

    //create Final product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'unit' => 'required',
            'selling_price' => 'required',
            'category' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $coop = Auth::user()->cooperative->id;
            $product = new FinalProduct();
            $product->name = $request->name;
            $product->category_id = $request->category;
            $product->cooperative_id = $coop;
            $product->unit_id = $request->unit;
            $product->selling_price = $request->selling_price;
            $product->save();
            toastr()->success('Final Product Created Successfully');
            $data = ['user_id' => Auth::user()->id, 'activity' => 'Created final product ' . $request->name, 'cooperative_id' => $coop];
            event(new AuditTrailEvent($data));
            DB::commit();
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error('Oops! Operation Failed');
            return redirect()->back()->withInput();
        }
    }


//    edit final product

    public function edit_final_product($id, Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'edit_name' => 'required',
            'edit_unit' => 'required',
            'edit_selling_price' => 'required',
            'edit_category' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $coop = Auth::user()->cooperative->id;
            $product = FinalProduct::findOrFail($id);
            $product->name = $request->edit_name;
            $product->category_id = $request->edit_category;
            $product->unit_id = $request->edit_unit;
            $product->selling_price = $request->edit_selling_price;
            $product->save();
            toastr()->success('Final Product Updated Successfully');
            $data = ['user_id' => Auth::user()->id, 'activity' => 'Updated final product ' . $product->name, 'cooperative_id' => $coop];
            event(new AuditTrailEvent($data));
            DB::commit();
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error('Oops! Operation Failed');
            return redirect()->back()->withInput();
        }

    }

    //raw materials
    public function allRawMaterials()
    {
        $coop = Auth::user()->cooperative->id;
        $materials = RawMaterial::where('cooperative_id', $coop)->latest()->get();
        $units = Unit::where('cooperative_id', $coop)->latest()->get();
        return view('pages.cooperative.manufacturing.raw-materials.index', compact('materials', 'units'));
    }


    public function rawMaterials($productionId, $productionHistoryId)
    {
        $production = Production::findOrFail($productionId);
        $productionLot = ProductionHistory::findOrFail($productionHistoryId)->production_lot;
        $coop = $production->cooperative_id;
        $production_materials = ProductionHistory::raw_material_used($coop, $productionHistoryId);
        $raw_materials = DB::select(
            "
                select r.name, sum(rmsh.quantity) as available_quanity, r.id  from raw_material_supply_histories rmsh
                join raw_materials r on rmsh.raw_material_id = r.id
                where r.cooperative_id ='$coop'                                                              
                group by  r.name, r.id having available_quanity > 0 order by  r.name desc
            "
        );
        return view('pages.cooperative.manufacturing.production.materials', compact('production_materials', 'production', 'raw_materials', 'productionHistoryId', 'productionLot'));
    }

    // create raw materials
    public function storeMaterial(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'units' => 'required',
            'estimated_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        try {
            $user = Auth::user();
            $product = new RawMaterial();
            $product->name = $request->name;
            $product->unit_id = $request->units;
            $product->estimated_cost = $request->estimated_cost;
            $product->cooperative_id = $user->cooperative_id;

            $product->save();
            toastr()->success('Raw material Created Successfully');
            $data = ['user_id' => $user->id, 'activity' => 'Created raw material ' . $product['id'], 'cooperative_id' => $user->cooperative_id];
            event(new AuditTrailEvent($data));
            return back();
        } catch (\Throwable $th) {
            Log::error($th->getTraceAsString());
            toastr()->error('Oops! Operation Failed');
            return redirect()->back()->withInput();
        }
    }

    public function edit_raw_material(Request $request, $id): \Illuminate\Http\RedirectResponse
    {

        $request->validate([
            'edit_name' => 'required',
            'edit_units' => 'required',
            'edit_estimated_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        try {
            $user = Auth::user();
            $product = RawMaterial::findOrFail($id);
            $product->name = $request->edit_name;
            $product->unit_id = $request->edit_units;
            $product->estimated_cost = $request->edit_estimated_cost;

            $product->save();
            toastr()->success('Raw material Updated Successfully');
            $data = ['user_id' => $user->id, 'activity' => 'Updated raw material ' . $id, 'cooperative_id' => $user->cooperative_id];
            event(new AuditTrailEvent($data));
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error($th->getTraceAsString());
            toastr()->error('Oops! Operation Failed');
            return redirect()->back()->withInput();
        }
    }

    public function getReports(Request $request)
    {
        $coop = Auth::user()->cooperative->id;
        $products = FinalProduct::where('cooperative_id', $coop)->orderBy('name')->get();
        $latest_stock = ProductionStockTracker::where('cooperative_id', $coop);
        $stock = ProductionStockTracker::where('cooperative_id', $coop);

        $available_stock_query = "select sum(p.available_quantity*fp.selling_price) as available_stock_value from productions p
                    join final_products fp on p.final_product_id = fp.id
                    where p.cooperative_id = '$coop'";
        $available_raw_materials_query = "select sum(if(delivery_status = 1, amount*quantity,0)) as available_raw_materials from
            raw_material_supply_histories where cooperative_id ='$coop'";
        $purchase_orders_query = "select sum(amount*quantity) as purchase_order from raw_material_supply_histories
            where cooperative_id ='$coop'";

        $expired_stock_query = "select SUM(IF(ph.expiry_status=2, ph.quantity*ph.unit_price, 0)) as expired_stock
                                from production_histories ph
                                join productions p on ph.production_id = p.id
                                where ph.cooperative_id = '$coop'";


        $manufacturing_total_sales_query = "select sum((si.quantity * si.amount)-s.discount)
                                        as manufacturing_sales from sale_items si
                                        join sales s on si.sales_id = s.id
                                        join productions p on si.manufactured_product_id = p.id
                                        where s.cooperative_id = '$coop'
                                        and si.manufactured_product_id is not null";


        if ($request->product) {
            $latest_stock = $latest_stock->where('final_product_id', $request->product);
            $stock = $stock->where('final_product_id', $request->product);
            $available_stock_query .= " and fp.id = '{$request->product}'";

            $final_product_id = $request->product;
            $materials_inner_query = "select pm.raw_material_id from production_materials pm
                                    join production_histories ph on pm.production_history_id = ph.id
                                    join productions p on ph.production_id = p.id
                                    where pm.cooperative_id = '$coop' and p.final_product_id = '$final_product_id' ";

            $available_raw_materials_query .= " and raw_material_id in ($materials_inner_query)";

            $purchase_orders_query .= " and raw_material_id in ($materials_inner_query)";
            $manufacturing_total_sales_query .= " and p.final_product_id = '$final_product_id'";
            $expired_stock_query .= " and p.final_product_id = '$final_product_id'";
        }


        if ($request->date) {
            $dates = split_dates($request->date);
            $from = $dates['from'];
            $to = $dates['to'];
            $latest_stock = $latest_stock->whereBetween('date', [$from, $to]);
            $stock = $stock->whereBetween('date', [$from, $to]);
            $available_stock_query .= " and (p.created_at between '$from' and '$to')";
            $available_raw_materials_query .= " and (supply_date between '$from' and '$to')";
            $purchase_orders_query .= " and (supply_date between '$from' and '$to')";
            $manufacturing_total_sales_query .= " and (s.date between '$from' and '$to')";
            $expired_stock_query .= " and (ph.created_at between '$from' and '$to')";
        }

        $latest_stock = $latest_stock->latest()->limit(100)->get();
        $stock = $stock->latest()->first();

        $available_stock = DB::select(
            $available_stock_query . " group by p.cooperative_id"
        );

        $available_raw_materials = DB::select(
            $available_raw_materials_query . " group by cooperative_id"
        );

        $purchase_orders = DB::select(
            $purchase_orders_query . " group by cooperative_id"
        );

        $manufacturing_total_sales = DB::select(
            $manufacturing_total_sales_query
        );

        $expired_stock = DB::select(
            $expired_stock_query
        );


        if (!empty($available_stock)) {
            $available_stock = $available_stock[0]->available_stock_value;
        } else {
            $available_stock = 0;
        }

        if (!empty($available_raw_materials)) {
            $available_raw_materials = $available_raw_materials[0]->available_raw_materials;
        } else {
            $available_raw_materials = 0;
        }

        if (!empty($purchase_orders)) {
            $purchase_orders = $purchase_orders[0]->purchase_order;
        } else {
            $purchase_orders = 0;
        }

        if (!empty($manufacturing_total_sales)) {
            $manufacturing_total_sales = $manufacturing_total_sales[0]->manufacturing_sales;
        } else {
            $manufacturing_total_sales = 0;
        }

        if (!empty($expired_stock)) {
            $expired_stock = $expired_stock[0]->expired_stock;
        } else {
            $expired_stock = 0;
        }


        return view('pages.cooperative.manufacturing.report',
            compact('stock', 'latest_stock',
                'available_stock', 'available_raw_materials',
                'purchase_orders', 'products', 'manufacturing_total_sales', 'expired_stock'));
    }

    //raw materials
    public function production()
    {
        $coop = Auth::user()->cooperative->id;
        $products = FinalProduct::where('cooperative_id', $coop)->latest()->get();
        $productions = Production::get_production_summery($coop, 100);
        $materials = RawMaterial::whereHas('cooperative', function ($query) use ($coop) {
            $query->where('cooperative_id', $coop);
        })->with('unit')->latest()->get();
        $stores = ManufacturingStore::where('cooperative_id', $coop)->latest()->get();
        return view('pages.cooperative.manufacturing.production.index', compact('products', 'productions', 'stores', 'materials'));
    }

    // create raw materials
    public function storeProduction(Request $request)
    {
        $request->validate([
            'quantity' => 'required',
            'product' => 'required',
            'expiry_date' => 'sometimes|nullable|required_if:will_expire,==,1|date|after:tomorrow',
            'materials' => 'required',
            'store' => 'required',
            'will_expire' => 'required'
        ]);
        try {

            DB::beginTransaction();
            $user = Auth::user();
            $coop = $user->cooperative->id;
            $finalProduct = FinalProduct::findOrFail($request->product);

            // check if in production table
            $production = Production::where('final_product_id', $finalProduct->id)
                ->where('cooperative_id', $coop)
                ->first();

            if ($production) {
                //if present update quantity and selling price
                $production->available_quantity += $request->quantity;
                $production->final_selling_price = $finalProduct->selling_price;
                $production->save();
                $production_id = $production->id;
            } else {
                // else create new production entry
                $production = new Production();
                $production->final_product_id = $finalProduct->id;
                $production->quantity = $request->quantity;
                $production->available_quantity = $request->quantity;
                $production->final_selling_price = $finalProduct->selling_price;
                $production->manufacturing_store_id = $request->store;
                $production->cooperative_id = $coop;
                $production->save();
                $production_id = $production->refresh()->id;
            }


            //save in production history
            $production_history = new ProductionHistory();
            $production_history->production_id = $production_id;
            $production_history->quantity = $request->quantity;
            $production_history->unit_price = $finalProduct->selling_price;
            $production_history->user_id = $user->id;
            $production_history->expiry_date = $request->will_expire == 1 ? $request->expiry_date : null;
            $production_history->expires = $request->will_expire;
            $production_history->cooperative_id = $coop;
            $production_history->production_lot = $this->generate_lot_number($coop);
            $production_history->save();

            //save production raw materials
            $materials = json_decode($request->materials);
            $not_enough_materials = [];
            foreach ($materials as $material) {
                $the_material = RawMaterialSupplyHistory::where('raw_material_id', $material->id)
                    ->where('cooperative_id', $coop)
                    ->where('quantity', '>', 0)
                    ->first();

                $supply_quantity = RawMaterialSupplyHistory::where('raw_material_id', $material->id)->sum('quantity');

                Log::info("Material Id {$the_material->raw_material_id}\nMaterial {$the_material->raw_material->name}\nAvailable Quantity {$supply_quantity}\nRequested Quantity {$material->the_quantity}");

                if ($the_material == null) {
                    toastr()->error('Raw Material ' . $material->name . ' does not exist');
                    return redirect()->back()->withInput();
                }

                if ($material->the_quantity > $supply_quantity) {
                    $not_enough_materials[] = $material->name;
                }

                $the_material->quantity -= $material->the_quantity;

                $the_material->save();
                $materials = new ProductionMaterial();
                $materials->raw_material_id = $material->id;
                $materials->production_history_id = $production_history->refresh()->id;
                $materials->quantity = $material->the_quantity;
                $materials->cooperative_id = $coop;
                $materials->cost = $material->estimated_cost ?? $material->product->sale_price;
                $materials->save();
            }

            if (count($not_enough_materials)) {
                DB::rollBack();
                $message = "Not enough raw materials for " . implode(", ", $not_enough_materials);
                toastr()->error($message);
                return redirect()->back()->withInput();
            }

            $data = ['user_id' => Auth::user()->id, 'activity' => 'Created Production ' . $production['id'] . 'for product ' . $production['final_product_id'], 'cooperative_id' => $coop];
            event(new AuditTrailEvent($data));
            DB::commit();
            toastr()->success('Production registered successfully');
            return back();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th->getMessage());
            Log::error($th->getTraceAsString());
            toastr()->error('Production failed to register');
            return back()->withInput();
        }
    }

    //create production materials
    public function addProductionRawMaterials(Request $request, $production_history_id)
    {

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'material' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $coop = $user->cooperative->id;

            $the_material = $this->validate_raw_materials($coop, $request->material, $request->quantity);

            $the_material->save();
            $materials = new ProductionMaterial();
            $materials->raw_material_id = $request->material;
            $materials->production_history_id = $production_history_id;
            $materials->quantity = $request->quantity;
            $materials->cooperative_id = $coop;
            $materials->cost = $the_material->raw_material->estimated_cost;
            $materials->save();
            $data = ['user_id' => $user->id, 'activity' => 'Added Production Raw material materials ', 'cooperative_id' => $coop];
            event(new AuditTrailEvent($data));
            DB::commit();
            toastr()->success('Raw material added successfully');
            return back();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th->getMessage());
            Log::error($th->getTraceAsString());
            toastr()->error('Oops Operation Failed');
            return back()->withInput();
        }
    }


    public function editProductionRawMaterials($id, Request $request)
    {

        $request->validate([
            'edit_quantity' => 'required',
            'edit_material' => 'required'
        ]);
        $production_raw_material = ProductionMaterial::findOrFail($id);

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $coop = $user->cooperative_id;
            $the_material = $this->validate_raw_materials($coop, $request->edit_material, $request->edit_quantity, true, $request->old_quantity);
            $the_material->save();
            $production_raw_material->quantity = $request->edit_quantity;
            $production_raw_material->raw_material_id = $request->edit_material;
            $production_raw_material->save();
            $data = ['user_id' => $user->id, 'activity' => 'Updated Production Raw material material', 'cooperative_id' => $coop];
            event(new AuditTrailEvent($data));
            DB::commit();
            toastr()->success('Raw material updated successfully');
            return back();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th->getTraceAsString());
            toastr()->error('Oops Operation Failed');
            return back()->withInput();
        }

    }


    private function validate_raw_materials($coop, $material_id, $quantity, $isEdit = false, $oldQuantity = null)
    {
        $the_material = RawMaterialSupplyHistory::where('raw_material_id', $material_id)
            ->where('cooperative_id', $coop)
            ->where('quantity', '>', 0)
            ->first();
        $all_materials = RawMaterialSupplyHistory::where('raw_material_id', $material_id)
            ->where('cooperative_id', $coop)
            ->where('quantity', '>', 0)
            ->sum('quantity');

        if ($the_material == null) {
            toastr()->error('Raw Material  does not exist');
            return redirect()->back()->withInput();
        }

        if ($all_materials < $quantity) {
            toastr()->error('You do not have enough quantity for ' . $the_material->raw_material->name);
            return redirect()->back()->withInput();
        }
        if ($isEdit) {
            if ($oldQuantity > $quantity) {
                $the_material->quantity += ($oldQuantity - $quantity);
            } else {
                $the_material->quantity -= $quantity;
            }

        } else {
            $the_material->quantity -= $quantity;
        }

        return $the_material;
    }

    public function show_production_history($id, Request $request)
    {
        $production = Production::findOrFail($id);
        $productionHistory = ProductionHistory::production_histories($production->id, $request, 100);
        return view('pages.cooperative.manufacturing.production.production-history', compact('productionHistory', 'production'));
    }


    public function export_manufacturing_production($type)
    {
        $cooperative_id = Auth::user()->cooperative->id;
        $productions = Production::get_production_summery($cooperative_id, 0);
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('manufacturing_registered_produced_products_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new ManufacturingProductionExport($productions), $file_name);
        } else {
            $data = [
                'title' => 'Manufacturing Registered Produced Products',
                'pdf_view' => 'manufacturing_production',
                'records' => $productions,
                'filename' => strtolower('manufacturing_registered_produced_products_' . date('d_m_Y')),
                'orientation' => 'portrait'
            ];
            return download_pdf($data);
        }
    }

    public function export_manufacturing_final_products($type)
    {
        $cooperative_id = Auth::user()->cooperative->id;
        if ($type != env('PDF_FORMAT')) {
            $file_name = 'manufacturing_final_products_' . date('d_m_Y') . '.' . $type;
            return Excel::download(new ManufacturingProductsExport($cooperative_id), $file_name);
        } else {
            $data = [
                'title' => 'Manufacturing Final Products',
                'pdf_view' => 'manufacturing_final_products',
                'records' => FinalProduct::where('cooperative_id', $cooperative_id)->latest()->get(),
                'filename' => 'manufacturing_final_products_' . date('d_m_Y'),
                'orientation' => 'portrait'
            ];
            return download_pdf($data);
        }
    }

    public function export_manufacturing_reports($type)
    {
        $cooperative_id = Auth::user()->cooperative_id;
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('manufacturing_reports_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new ManufacturingReportExport($cooperative_id), $file_name);
        } else {
            $data = [
                'title' => 'Manufacturing Reports',
                'pdf_view' => 'manufacturing_reports',
                'records' => Production::whereHas('finalProduct', function ($query) use ($cooperative_id) {
                    $query->where('cooperative_id', $cooperative_id);
                })->with(['finalProduct'])->latest()->get(),
                'filename' => strtolower('manufacturing_reports_' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function export_raw_materials($type)
    {
        $cooperative = Auth::user()->cooperative->id;
        $raw_materials = RawMaterial::where('cooperative_id', $cooperative)->latest()->get();
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('manufacturing_raw_materials_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new ManufacturingRawMaterialsExport($raw_materials), $file_name);
        } else {
            $data = [
                'title' => 'Manufacturing Raw Materials',
                'pdf_view' => 'manufacturing_raw_materials',
                'records' => $raw_materials,
                'filename' => strtolower('manufacturing_raw_materials_' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function manufacturing_stores()
    {
        $user = Auth::user();
        $stores = ManufacturingStore::where('cooperative_id', $user->cooperative_id)->latest()->limit(100)->get();
        return view('pages.cooperative.manufacturing.store.index', compact('stores'));
    }

    public function add_manufacturing_stores(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
        ]);
        $user = Auth::user();
        try {
            DB::beginTransaction();
            $store = new ManufacturingStore();
            $store->name = $request->name;
            $store->location = $request->location;
            $store->cooperative_id = $user->cooperative_id;
            $store->save();

            $data = ['user_id' => $user->id, 'activity' => 'Added a new store: ' . $request->name, 'cooperative_id' => $user->cooperative_id];
            event(new AuditTrailEvent($data));
            DB::commit();
            toastr()->success('Store Added Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error("Error: " . $th->getMessage());
            Log::error("Trace: " . $th->getTraceAsString());
            DB::rollBack();
            toastr()->error('Oops! Error Occurred');
            return redirect()->back()->withInput();
        }
    }


    public function edit_manufacturing_stores(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'edit_name' => 'required|string',
            'edit_location' => 'required|string',
        ]);
        $user = Auth::user();
        try {
            DB::beginTransaction();
            $store = ManufacturingStore::findOrFail($id);
            $store->name = $request->edit_name;
            $store->location = $request->edit_location;
            $store->updated_at = Carbon::now();
            $store->save();

            $data = ['user_id' => $user->id, 'activity' => 'Updated a store: ' . $request->name, 'cooperative_id' => $user->cooperative_id];
            event(new AuditTrailEvent($data));
            DB::commit();
            toastr()->success('Store Updated Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error("Error: " . $th->getMessage());
            Log::error("Trace: " . $th->getTraceAsString());
            DB::rollBack();
            toastr()->error('Oops! Error Occurred');
            return redirect()->back()->withInput();
        }
    }

    public function export_stores($type)
    {
        $cooperative = Auth::user()->cooperative->id;

        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('stores_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new \App\Exports\ManufacturingStore($cooperative), $file_name);
        } else {
            $data = [
                'title' => 'Manufacturing Stores',
                'pdf_view' => 'manufacturing_stores',
                'records' => ManufacturingStore::where('cooperative_id', $cooperative)->latest()->get(),
                'filename' => strtolower('stores_' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function manufacturing_supply()
    {
        $user = Auth::user();
        $raw_materials = RawMaterial::select(
            ['raw_materials.id', 'raw_materials.name', 'units.name as units', 'raw_materials.estimated_cost'])
            ->join('units', 'units.id', '=', 'raw_materials.unit_id')
            ->where('raw_materials.cooperative_id', $user->cooperative_id)
            ->orderBy('raw_materials.created_at', 'desc')
            ->get();
        $suppliers = Supplier::select(['id', 'name'])->where('cooperative_id', $user->cooperative_id)->latest()->get();
        $cooperative_id = $user->cooperative_id;
        $collection_products = DB::select(
            "
                    SELECT c.product_id AS id, sum(c.available_quantity) AS quantity, p.name  FROM collections c
                    INNER JOIN products p ON c.product_id = p.id WHERE c.cooperative_id = '$cooperative_id' AND  quantity > 0
                                                 GROUP BY c.product_id, p.name
                                                 ORDER BY p.name;
                   ");
        $supplies = RawMaterialSupplyHistory::supplies($cooperative_id);
        $stores = ManufacturingStore::where('cooperative_id', $user->cooperative_id)->latest()->get();
        return view('pages.cooperative.manufacturing.supplies', compact('raw_materials', 'supplies', 'suppliers', 'collection_products', 'stores'));
    }


    public function manufacturing_supply_details($raw_material_id, Request $request)
    {
        $user = Auth::user();
        $coop = $user->cooperative_id;
        $supplies = RawMaterialSupplyHistory::supply_histories($request, $user->cooperative_id, $raw_material_id, 100);
        $stores = ManufacturingStore::where('cooperative_id', $user->cooperative_id)->latest()->get();
        $products = Product::where('cooperative_id', $coop)->orderBy('name')->get();
        $suppliers = Supplier::select(['id', 'name'])->where('cooperative_id', $user->cooperative_id)->latest()->get();

        return view('pages.cooperative.manufacturing.supply-details',
            compact('supplies', 'raw_material_id', 'stores',
                'products', 'suppliers'));
    }


    public function add_manufacturing_supply(Request $request)
    {

        $request->validate([
            'supply_type' => 'required',
            'collection' => 'required_if:supplier_type,==,1',
            'supplier' => 'required_if:supplier_type,==,2',
            'raw_material' => 'required',
            'quantity' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'supply_date' => 'required|date',
            'payment_status' => 'required_if:supplier_type,==,2',
            'store' => 'required',
            'notes' => 'sometimes|nullable|string',
            'paid_amount' => 'sometimes|nullable|required_if:payment_status,==,3',
            'delivery_status' => 'sometimes|nullable|required_if:supplier_type,==,2',
        ], [
            'supply_date.before' => 'The supply date must not be in a future date',
            'amount.required' => 'Unit price is required',
            'amount.regex' => 'Unit price format is invalid',
            'paid_amount.required_if' => 'The paid amount field is required when payment status is Partial'
        ]);

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $cooperative_id = $user->cooperative_id;
            $product_id = $request->collection;
            $amount = $request->quantity * $request->amount;

            if ($request->supply_type == RawMaterialSupplyHistory::SUPPLY_TYPE_COLLECTION) {

                if ($request->paid_amount && $request->paid_amount > $amount) {
                    return redirect()->back()->withInput()->withErrors(['paid_amount' => 'Paid Amount cannot be more than the amount to be paid.']);
                }

                $product = DB::select(
                    "
                    SELECT sum(c.available_quantity) AS quantity FROM collections c
                    INNER JOIN products p ON c.product_id = p.id WHERE c.cooperative_id = '$cooperative_id' AND   c.product_id = '$product_id'
                                                 GROUP BY c.product_id LIMIT 1;
                   ")[0];

                if ($product) {
                    if ($product->quantity < $request->quantity) {
                        toastr()->error('Quantity provided is more than the quantity in store');
                        return redirect()->back()->withInput()->withErrors(['quantity' => "Quantity provided {$request->quantity} more than the quantity in store {$product->quantity}"]);
                    }
                } else {
                    toastr()->error('Collection product not in store');
                    return redirect()->back()->withInput()->withErrors(['collection' => 'Collection product not in store']);
                }

                //update the collection quantity
                $collection_to_update = Collection::where('product_id', $product_id)
                    ->where('available_quantity', '>', 0)
                    ->where('cooperative_id', $cooperative_id)
                    ->first();

                $collection_to_update->available_quantity -= $request->quantity;
                $collection_to_update->save();
            }
            $raw_material_id = json_decode($request->raw_material)->id;
            $supply_history = new RawMaterialSupplyHistory();
            $supply_history->cooperative_id = $cooperative_id;
            $supply_history->raw_material_id = $raw_material_id;
            $supply_history->supply_type = $request->supply_type;
            $supply_history->supplier_id = $request->supplier;
            $supply_history->product_id = $product_id;
            $supply_history->supply_date = $request->supply_date;
            $supply_history->quantity = $request->quantity;
            $supply_history->details = $request->notes;
            $supply_history->user_id = $user->id;
            $supply_history->store_id = $request->store;
            $supply_history->purchase_number = $this->generate_purchase_number($cooperative_id);


            if ($request->supply_type == RawMaterialSupplyHistory::SUPPLY_TYPE_COLLECTION) {
                $supply_history->balance = 0;
                $supply_history->amount = $amount;
                $supply_history->payment_status = RawMaterialSupplyHistory::PAYMENT_STATUS_PAID;
                $supply_history->delivery_status = RawMaterialSupplyHistory::DELIVERY_STATUS_DELIVERED;
            } else {
                $supply_history->delivery_status = $request->delivery_status;
                if ($request->payment_status == RawMaterialSupplyHistory::PAYMENT_STATUS_PAID) {
                    $supply_history->balance = 0;
                } elseif ($request->payment_status == RawMaterialSupplyHistory::PAYMENT_STATUS_PARTIAL) {
                    $supply_history->balance = $amount - $request->paid_amount;
                } else {
                    $supply_history->balance = $amount;
                }
                $supply_history->amount = $amount;
                if ($amount == $request->paid_amount) {
                    $supply_history->payment_status = RawMaterialSupplyHistory::PAYMENT_STATUS_PAID;
                } else {
                    $supply_history->payment_status = $request->payment_status;
                }
            }

            $supply_history->save();
            $supply_history_id = $supply_history->refresh()->id;

            $raw_material = RawMaterial::findOrFail($raw_material_id);

            // update ledgers
            $raw_materials_value = $request->input('quantity') * $request->input('amount');
            $amount_paid = $request->input('paid_amount');

            create_account_transaction('Purchase Raw Materials', $raw_materials_value, "Purchase of raw materials: {$raw_material->name}");
            if ($request->supply_type == RawMaterialSupplyHistory::SUPPLY_TYPE_COLLECTION) {
                $amount_paid = $amount_paid ?? $raw_materials_value;
                create_account_transaction('Supplier Payment', $amount_paid, 'Supplier payment');
                $this->record_supply_payment(
                    $supply_history_id,
                    $amount,
                    $supply_history->balance,
                    $user->cooperative_id
                );
            } else {
                if ($supply_history->payment_status == RawMaterialSupplyHistory::PAYMENT_STATUS_PAID) {
                    create_account_transaction('Supplier Payment', $amount_paid, 'Supplier payment');
                    $this->record_supply_payment(
                        $supply_history_id,
                        $amount,
                        $supply_history->balance,
                        $user->cooperative_id
                    );
                }

                if ($supply_history->payment_status == RawMaterialSupplyHistory::PAYMENT_STATUS_PARTIAL) {
                    $amount = $request->paid_amount;
                    create_account_transaction('Supplier Payment', $amount_paid, 'Supplier payment');
                    $this->record_supply_payment(
                        $supply_history_id,
                        $amount,
                        $supply_history->balance,
                        $user->cooperative_id
                    );
                }
            }

            $description = "Record Supply of {$raw_material->name} of {$request->quantity} {$raw_material->unit->name} for {$request->amount} per unit by {$user->first_name}";
            $audit_trail_data = ['user_id' => $user->id, 'activity' => $description, 'cooperative_id' => $cooperative_id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Supply Recorded Successfully');
            DB::commit();
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            Log::error($th->getTraceAsString());
            DB::rollBack();
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }

    }

    private function generate_purchase_number($cooperativeId): string
    {
        $latest_supply_history = RawMaterialSupplyHistory::where('cooperative_id', $cooperativeId)
            ->latest()
            ->first();
        $purchase_number = 'PO' . date('Ymd') . '-';
        if ($latest_supply_history) {
            $latest_order_number = explode('-', $latest_supply_history->purchase_number)[1];
            $purchase_number .= ++$latest_order_number;
        } else {
            $purchase_number .= 1;
        }

        return $purchase_number;
    }

    private function record_supply_payment($supply_history_id, $amount, $balance, $cooperative)
    {
        $supply_history = new RawMaterialSupplyPayment();
        $supply_history->cooperative_id = $cooperative;
        $supply_history->amount = $amount;
        $supply_history->balance = $balance;
        $supply_history->supply_history_id = $supply_history_id;
        $supply_history->save();
    }

    public function mark_purchase_orders_as_paid($id, Request $request): \Illuminate\Http\RedirectResponse
    {

        $request->validate([
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $raw_material_history = RawMaterialSupplyHistory::findOrFail($id);
            $amount = $request->amount;
            if ($amount > $raw_material_history->balance) {
                toastr()->error(
                    'Amount is more than the owed amount. Enter amount less than or equals to ' .
                    $raw_material_history->balance
                );
                return redirect()->back()->withErrors(['amount' => 'Amount is more than the owed amount.']);
            }

            $raw_material_history->balance -= $amount;
            $this->record_supply_payment($id, $amount, $raw_material_history->balance, $user->cooperative_id);
            if ($raw_material_history->balance > 0) {
                $raw_material_history->payment_status = RawMaterialSupplyHistory::PAYMENT_STATUS_PARTIAL;
            } else {
                $raw_material_history->payment_status = RawMaterialSupplyHistory::PAYMENT_STATUS_PAID;
            }

            $amount_paid = $request->input('amount_paid');

            $raw_material_history->updated_at = Carbon::now();
            $raw_material_history->save();
            $amount = $raw_material_history->amount;
            $description = 'Payment done for supply of  ' . $raw_material_history->raw_material->name . ' of ' . number_format($raw_material_history->quantity) . ' ' . $raw_material_history->raw_material->unit->name . ' for ' . $user->cooperative->currency . ' ' . number_format($amount) . ' by ' . $raw_material_history->user->first_name;
            create_account_transaction('Supplier Payment', $amount_paid, $description);
            $audit_trail_data = ['user_id' => $user->id, 'activity' => $description, 'cooperative_id' => $user->cooperative_id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Purchase completed!');
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            DB::rollBack();
            toastr()->error('Oops! Operation failed');
            return redirect()->back();
        }

    }

    public function edit_raw_material_supplies($id, Request $request)
    {
        $request->validate([
            'edit_mount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'edit_quantity' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'edit_supply_date' => 'required|date',
            'edit_store' => 'required',
            'edit_delivery_status' => 'required',
            'edit_notes' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $supply_history = RawMaterialSupplyHistory::findOrFail($id);
            $supply_history->user_id = $user->id;
            $supply_history->quantity = $request->edit_quantity;
            $supply_history->amount = $request->edit_mount;
            $supply_history->balance = $request->edit_mount;
            $supply_history->supply_date = $request->edit_supply_date;
            $supply_history->store_id = $request->edit_store;
            $supply_history->delivery_status = $request->edit_delivery_status;
            $supply_history->details = $request->edit_notes;
            $supply_history->save();

            $description = 'Updated Raw materials ' . $supply_history->raw_material->name . ' by ' . $user->first_name;
            $audit_trail_data = [
                'user_id' => $user->id,
                'activity' => $description,
                'cooperative_id' => $user->cooperative_id
            ];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Purchase updated!');
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            DB::rollBack();
            toastr()->error('Oops! Operation failed');
            return redirect()->back();
        }

    }

    public function mark_goods_as_recieved($supply_history_id)
    {
        $supply_history = RawMaterialSupplyHistory::findOrFail($supply_history_id);
        $user = Auth::user();
        $supply_history->delivery_status = RawMaterialSupplyHistory::DELIVERY_STATUS_DELIVERED;
        $supply_history->save();
        $description = ' Raw materials Received' . $supply_history->raw_material->name . ' by ' . $user->first_name;
        $audit_trail_data = [
            'user_id' => $user->id,
            'activity' => $description,
            'cooperative_id' => $user->cooperative_id
        ];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Purchase updated!');
        return redirect()->back();
    }

    public function export_supplies($type)
    {
        $cooperative = Auth::user()->cooperative->id;
        $supplies = RawMaterialSupplyHistory::supplies($cooperative);

        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('supplies_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new \App\Exports\ManufacturingSupplyExport($supplies), $file_name);
        } else {
            $data = [
                'title' => 'Raw Materials Supplies',
                'pdf_view' => 'raw_material_supplies',
                'records' => $supplies,
                'filename' => strtolower('supplies_' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function export_supply_details($raw_material_id, $type, Request $request)
    {
        if ($request->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($request->request_data);
        }
        $cooperative = Auth::user()->cooperative->id;
        $supplies = RawMaterialSupplyHistory::supply_histories(
            $request,
            $cooperative,
            $raw_material_id,
            null);
        $raw_material = RawMaterial::findOrFail($raw_material_id);
        $file_name_prefix = strtolower($raw_material->name . '_supplies_' . date('d_m_Y'));

        if ($type != env('PDF_FORMAT')) {
            $file_name = $file_name_prefix . '.' . $type;
            return Excel::download(new ManufacturingSupplyDetailsExport($supplies), $file_name);
        } else {
            $data = [
                'title' => $raw_material->name . ' Supply Details',
                'pdf_view' => 'raw_material_supply_details',
                'records' => $supplies,
                'filename' => $file_name_prefix,
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function supplier_supplies($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        $supplies = RawMaterialSupplyHistory::where('supplier_id', $supplierId)
            ->latest()->limit(100)->get();
        return view('pages.cooperative.manufacturing.supplier.supplies', compact('supplies', 'supplier'));
    }

    public function export_supplier_supplies($supplierId, $type)
    {
        $supplies = RawMaterialSupplyHistory::where('supplier_id', $supplierId)
            ->latest()->get();
        $supplier = Supplier::findOrFail($supplierId);
        $file_name_prefix = strtolower('supplier_' . '_supplies_' . date('d_m_Y'));

        if ($type != env('PDF_FORMAT')) {
            $file_name = $file_name_prefix . '.' . $type;
            return Excel::download(new \App\Exports\ManfucturingSupplierSupplies($supplies), $file_name);
        } else {
            $data = [
                'title' => $supplier->name . ' Supplies',
                'pdf_view' => 'supplier_supplies',
                'records' => $supplies,
                'filename' => $file_name_prefix,
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    /**
     * @throws \Exception
     */
    public function download_purchase_orders_receipt($supply_history_id)
    {
        $user = Auth::user();
        $supply = RawMaterialSupplyHistory::findOrFail($supply_history_id);
        $supply_payments = RawMaterialSupplyPayment::where('supply_history_id', $supply_history_id)->latest()->get();

        $client = new Party([
            'name' => $user->cooperative->name,
            'phone' => $user->cooperative->contact_details,
            'email' => $user->cooperative->email,
            'custom_fields' => [
                'address' => $user->cooperative->address,
                'Served By' => ucwords(strtolower($supply->user->first_name . ' ' . $supply->user->other_names)),
                'Generated By' => ucwords(strtolower($user->first_name . ' ' . $user->other_names)),
            ],
        ]);


        $customer = new Buyer([
            'name' => $supply->supply_type == RawMaterialSupplyHistory::SUPPLY_TYPE_SUPPLIER ?
                $supply->supplier->name : 'Internal',
            'custom_fields' => [
                'email' => $supply->supply_type == RawMaterialSupplyHistory::SUPPLY_TYPE_SUPPLIER ?
                    $supply->supplier->email : 'Internal',
                'address' => $supply->supply_type == RawMaterialSupplyHistory::SUPPLY_TYPE_SUPPLIER ?
                    $supply->supplier->address : 'Internal',
            ],
        ]);

        $items = [];
        if (count($supply_payments) > 0) {
            foreach ($supply_payments as $item) {
                $items[] = (new InvoiceItem())->title(Carbon::parse($item->created_at)->format('D, d M Y'))
                    ->subTotalPrice($item->amount)
                    ->pricePerUnit(0)
                    ->discount(0);
            }
            $status = $supply->payment_status == RawMaterialSupplyHistory::PAYMENT_STATUS_PAID ?
                'Paid' : 'Partially Paid';
        } else {
            $items[] = (new InvoiceItem())->title(Carbon::now()->format('D, d M Y'))
                ->subTotalPrice(0)
                ->pricePerUnit(0)
                ->discount(0);
            $status = 'Unpaid';
        }

        $invoice_name = $supply->raw_material->name . " Purchase Receipt";
        $currency = $user->cooperative->currency ?? 'KES';

        if ($supply->balance > 0) {
            $balance = number_format($supply->balance, 2);
            $notes = "Balance $currency $balance";
        } else {
            $notes = '';
        }


        $invoice = Invoice::make()
            ->name($invoice_name)
            ->status(__($status))
            ->seller($client)
            ->serialNumberFormat($supply->purchase_number)
            ->buyer($customer)
            ->currencySymbol($currency)
            ->currencyThousandsSeparator(',')
            ->addItems($items)
            ->logo(public_path($user->cooperative->logo ?? 'assets/images/favicon.png'))
            ->notes($notes)
            ->template('supply_receipt');

        return $invoice->stream();
    }

    private function generate_lot_number($cooperativeId): string
    {
        $latest_supply_history = ProductionHistory::where('cooperative_id', $cooperativeId)
            ->latest()
            ->first();

        $purchase_number = 'P' . date('Ymd') . '-';
        if ($latest_supply_history) {
            $latest_order_number = explode('-', $latest_supply_history->production_lot)[1];
            $purchase_number .= ++$latest_order_number;
        } else {
            $purchase_number .= 1;
        }

        return $purchase_number;
    }

    public function export_production_history($id, $type, Request $request)
    {
        if ($request->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($request->request_data);
        }
        $production_histories = ProductionHistory::production_histories($id, $request, null);
        if ($type != env('PDF_FORMAT')) {
            $file_name = 'production_history_' . date('d_m_Y') . '.' . $type;
            return Excel::download(new ProductionHistoryExport($production_histories), $file_name);
        } else {
            $data = [
                'title' => 'Manufacturing Production History',
                'pdf_view' => 'manufacturing_production_history',
                'records' => $production_histories,
                'filename' => 'production_history_' . date('d_m_Y'),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function export_production_raw_materials($productionHistoryId, $type)
    {
        $cooperative_id = Auth::user()->cooperative->id;
        $production_histories_raw_materials = ProductionHistory::raw_material_used($cooperative_id, $productionHistoryId);
        $productionHistory = ProductionHistory::findOrFail($productionHistoryId);

        if ($type != env('PDF_FORMAT')) {
            $file_name = 'production_history_raw_materials_' . date('d_m_Y') . '.' . $type;
            return Excel::download(
                new ProductionHistoryRawMaterialExport($production_histories_raw_materials),
                $file_name);
        } else {
            $data = [
                'title' => 'Production History Lot #' . $productionHistory->production_lot . ' Raw materials',
                'pdf_view' => 'manufacturing_production_history_raw_materials',
                'records' => $production_histories_raw_materials,
                'filename' => 'production_history_raw_materials_' . date('d_m_Y'),
                'orientation' => 'portrait'
            ];
            return download_pdf($data);
        }
    }

    public function get_by_store($storeId, Request $request)
    {
        $user = Auth::user();
        $coop = $user->cooperative->id;
        $store = ManufacturingStore::findOrFail($storeId);
        $supplies = RawMaterialSupplyHistory::supply_history_by_store($request, $coop, 100, $storeId);
        $products = Product::where('cooperative_id', $coop)->orderBy('name')->get();
        $suppliers = Supplier::select(['id', 'name'])->where('cooperative_id', $coop)->latest()->get();
        $productionHistory = ProductionHistory::production_history_by_store($storeId, $request, 100);
        $raw_materials = RawMaterial::where('cooperative_id', $coop)->latest()->get();

        return view('pages.cooperative.manufacturing.store.data',
            compact('supplies', 'products', 'suppliers',
                'productionHistory', 'store','raw_materials'));
    }

    public function export_production_history_by_store($storeId, $type, Request $request)
    {
        if ($request->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($request->request_data);
        }
        $production_histories = ProductionHistory::production_history_by_store($storeId, $request, null);

        if ($type != env('PDF_FORMAT')) {
            $file_name = 'production_history_' . date('d_m_Y') . '.' . $type;
            return Excel::download(new ProductionHistoryExport($production_histories), $file_name);
        } else {
            $store = ManufacturingStore::findOrFail($storeId);
            $data = [
                'title' => 'Manufacturing Final Products Stored in ' . $store->name,
                'pdf_view' => 'manufacturing_production_history',
                'records' => $production_histories,
                'filename' => 'production_history_' . date('d_m_Y'),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function export_supplies_by_store($storeId, $type, Request $request)
    {
        if ($request->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($request->request_data);
        }
        $cooperative = Auth::user()->cooperative->id;
        $supplies = RawMaterialSupplyHistory::supply_history_by_store(
            $request,
            $cooperative,
            null,
            $storeId);

        $file_name_prefix = strtolower('supplies_by_store' . date('d_m_Y'));

        if ($type != env('PDF_FORMAT')) {
            $file_name = $file_name_prefix . '.' . $type;
            return Excel::download(new ManufacturingSupplyDetailsExport($supplies), $file_name);
        } else {
            $store = ManufacturingStore::findOrFail($storeId);
            $data = [
                'title' => 'Supplies Stored in ' . $store->name,
                'pdf_view' => 'raw_material_supply_details',
                'records' => $supplies,
                'filename' => $file_name_prefix,
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function expired_stock(Request $request){
        $coop_id = Auth::user()->cooperative_id;
        $finalProducts = FinalProduct::select(['id', 'name'])
            ->where('cooperative_id', $coop_id)
            ->orderBy('name')
            ->get();
        $expired_products = ProductionHistory::expired_products($coop_id,$request, 100);
        return view('pages.cooperative.manufacturing.production.expired-stock',
            compact('expired_products', 'finalProducts'));

    }

    public function expired_stock_download($type, Request $request){
        if ($request->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($request->request_data);
        }
        $coop = Auth::user()->cooperative_id;
        $production_histories = ProductionHistory::expired_products($coop, $request, null);
        if ($type != env('PDF_FORMAT')) {
            $file_name = 'expired_stock_' . date('d_m_Y') . '.' . $type;
            return Excel::download(new ExpiredStockExport($production_histories), $file_name);
        } else {
            $data = [
                'title' => 'Manufacturing Expired Stock',
                'pdf_view' => 'manufacturing_expired_stock',
                'records' => $production_histories,
                'filename' => 'expired_stock_' . date('d_m_Y'),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

}
