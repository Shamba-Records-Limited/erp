<?php

namespace App\Http\Controllers\Admin;

use App\AuctionOrderDelivery;
use App\Cooperative;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\MillerAuctionOrder;
use App\MillerAuctionOrderItem;
use App\PreMilledInventory;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;

class OrdersController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        $orders = MillerAuctionOrder::query()
        ->join('auction_order_delivery', 'miller_auction_order.id', '=', 'auction_order_delivery.order_id')
        ->join('millers', 'miller_auction_order.miller_id', '=', 'millers.id') // Join millers table
        ->join('miller_warehouse', 'miller_auction_order.miller_warehouse_id', '=', 'miller_warehouse.id') // Join miller_warehouse table
        ->with('cooperative') // Assuming cooperative is a relationship
        ->select(
            'miller_auction_order.*', 
            'auction_order_delivery.delivery_number', 'auction_order_delivery.created_at as delivery_date',
            'auction_order_delivery.approved_at as delivery_approval_date',
            'millers.name as miller_name', // Include columns from millers table
            'miller_warehouse.name as warehouse_name' // Include columns from miller_warehouse table
        )
        ->get();
    
    
        return view('pages.admin.orders.index', compact('orders'));
    }

    public function view_create_order($coop_id)
    {
        $cooperative = Cooperative::find($coop_id);

        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            throw $th;
        }

        $user_id = Auth::id();

        // get draft order
        $draftOrder = MillerAuctionOrder::where('published_at', null)
           // ->where("user_id", $user_id)
           // ->where("miller_id", $miller_id)
            //->where("cooperative_id", $coop_id)
            ->get();

        if (is_null($draftOrder)) {
            $now = Carbon::now();
            $today = $now->format('Y-m-d');
            $batchPrefix = $now->format('Ymd');

            $ordersCreatedToday = DB::select(DB::raw("
                SELECT count(1) AS count FROM miller_auction_order WHERE created_at > :today
            "), ["today" => $today])[0]->count;

            $batchInd = $ordersCreatedToday + 1;

            $batch_number = "BAT" . $batchPrefix . $batchInd;

            // default batch number
            $draftOrder = new MillerAuctionOrder();
            $draftOrder->batch_number = $batch_number;
            $draftOrder->miller_id = $miller_id;
            $draftOrder->cooperative_id = $coop_id;
            $draftOrder->user_id = $user_id;
            $draftOrder->save();
        }


        return view('pages.admin.orders.create.index', compact('draftOrder', 'cooperative'));
    }

    public function detail($id, Request $request)
    {

        // total in order
        $totalInOrder = DB::select(DB::raw("
            SELECT sum(item.quantity) AS total
            FROM miller_auction_order_item item
            WHERE item.order_id = :order_id
        "), ["order_id" => $id])[0]->total;

        // aggregate grade distribution
        $aggregateGradeDistribution = DB::select(DB::raw("
            SELECT SUM(d.quantity) AS total,
                pg.name AS grade
            FROM lot_grade_distributions d
            JOIN product_grades pg ON pg.id = d.product_grade_id
            JOIN lots l ON l.lot_number = d.lot_number
            JOIN miller_auction_order_item item ON item.lot_number = l.lot_number
            WHERE item.order_id = :order_id
            GROUP BY d.product_grade_id
            ORDER BY total DESC
        "), ["order_id" => $id,]);

        $order = null;
        $orders = DB::select(DB::raw("
            SELECT ord.*,
                coop.name as cooperative_name
            FROM miller_auction_order ord
            JOIN cooperatives coop ON coop.id = ord.cooperative_id
            WHERE ord.id = :id;
        "), ["id" => $id]);

        if (count($orders) > 0) {
            $order = $orders[0];
        }


        $tab = $request->query('tab', 'items');
        $delivery_id_to_view = $request->query('delivery_id_to_view', '');

        $orderItems = [];
        if ($tab == 'items') {
            $orderItems = DB::select(DB::raw("
                SELECT item.*
                    FROM miller_auction_order_item item
                WHERE item.order_id = :order_id;
            "), ["order_id" => $id]);
        }

        $orderDeliveries = [];
        if ($tab == 'deliveries') {
            $orderDeliveries = DB::select(DB::raw("
                SELECT delivery.*,
                    (SELECT count(1) FROM auction_order_delivery_item item WHERE item.delivery_id = delivery.id) AS total_items,
                    delivery.published_at,
                    delivery.approved_at
                FROM auction_order_delivery delivery
                WHERE delivery.order_id = :order_id AND
                    delivery.published_at IS NOT NULL
            "), ["order_id" => $id]);
        }

        $delivery_to_view = null;
        $deliveryItems = [];
        if ($delivery_id_to_view) {
           // $delivery_to_view = AuctionOrderDelivery::find($delivery_id_to_view);
            
            $delivery_to_view = AuctionOrderDelivery::join('users', 'auction_order_delivery.user_id', '=', 'users.id')
                   ->where('auction_order_delivery.id', $delivery_id_to_view)
                   ->select('auction_order_delivery.*', 'users.first_name as first_name', 'users.other_names as other_names') // Select fields
                  ->first();


            $deliveryItems = DB::select(DB::raw("
                SELECT item.*, order_item.lot_number
                FROM auction_order_delivery_item item
                JOIN miller_auction_order_item order_item ON order_item.id = item.order_item_id
                WHERE item.delivery_id = :delivery_id;
            "), ["delivery_id" => $delivery_id_to_view]);
        }
        

        return view('pages.admin.orders.detail', compact('order', 'tab', 'orderItems', 'orderDeliveries', 'delivery_to_view', 'deliveryItems', 'totalInOrder', 'aggregateGradeDistribution'));
    }

    public function return_order_row(Request $request, $item_id)
    {
        return view('pages.admin.orders.create.order-row');
    }

    public function create_order_row(Request $request, $coop_id)
    {
        $user = Auth::user();

        $user_id = Auth::id();
        // get draft order
        $draftOrder = MillerAuctionOrder::where('published_at', null)
            //->where("user_id", $user_id)
            //->where("miller_id", $miller_id)
            //->where("cooperative_id", $coop_id)
            ->get();

        $item = new MillerAuctionOrderItem();
        $item->order_id = $draftOrder->id;
        $item->save();

        return redirect()->route("miller-admin.market-auction.coop-collections.render-order-row", $item->id);
    }



    public function reject_delivery($delivery_id)
    {
        $user = Auth::user();

        DB::beginTransaction();
        try {
            $user_id = Auth::id();
            $delivery = AuctionOrderDelivery::find($delivery_id);
            $delivery->approved_at = Carbon::now();
            $delivery->approved_by = $user_id;
            $delivery->delivery_number=$delivery->delivery_number."-REJECTED";
            $delivery->save();

            $order_id=$delivery->order_id;
            $delivery->items = MillerAuctionOrderItem::where('order_id', $order_id)->get();
	        //dd($delivery->items);
            foreach ($delivery->items as $delivery_item) {
				   $id = $delivery_item->id; 
					$miller_auction_order_item = MillerAuctionOrderItem::where('id', $id)->first();
                   // dd($miller_auction_order_item);
					if ($miller_auction_order_item){
						// Set quantity to 0
						$miller_auction_order_item->quantity = 0;
					    $miller_auction_order_item->save();
					  } 
               }
			   
            DB::commit();
            toastr()->success('Delivery Rejected');
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to initialize cart');
            return redirect()->back();
        }
    }

    public function approve_delivery($delivery_id)
    {

        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            throw $th;
        }

        DB::beginTransaction();
        try {
            $user_id = Auth::id();
            $delivery = AuctionOrderDelivery::find($delivery_id);
            $delivery->approved_at = Carbon::now();
            $delivery->approved_by = $user_id;
            $delivery->save();

            $now = Carbon::now();
            $inventoryNumber = "INV";
            $inventoryNumber .= $now->format('Ymd');

            // count today's inventories
            $todaysInventories = PreMilledInventory::where(DB::raw("DATE(created_at)"), $now->format('Y-m-d'))->count();

            $ind = 1;
            foreach ($delivery->items as $delivery_item) {
                $inventoryNumber .= str_pad($todaysInventories + $ind, 3, '0', STR_PAD_LEFT);
                $ind += 1;

                $pre_milled_inventory = new PreMilledInventory();
                $pre_milled_inventory->miller_id = $miller_id;
                $pre_milled_inventory->delivery_id = $delivery->id;
                $pre_milled_inventory->delivery_item_id = $delivery_item->id;
                $pre_milled_inventory->inventory_number = $inventoryNumber;
                $pre_milled_inventory->user_id = $user_id;
                $pre_milled_inventory->quantity = $delivery_item->quantity;
                $pre_milled_inventory->unit = $delivery_item->unit;
                $pre_milled_inventory->save();
            }

            DB::commit();
            toastr()->success('Delivery approved');
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to initialize cart');
            return redirect()->back();
        }
    }

    public function export_orders($type)
    {
        $user = Auth::user();
        $miller_id = null;
        if ($user->miller_admin) {
            $miller_id = $user->miller_admin->miller_id;
        }

        $rawOrders = MillerAuctionOrder::all();

        $orders = [];
        // todo: format data
        foreach ($rawOrders as $rawOrder) {
            if ($rawOrder->deliveredQuantity == 0 || $rawOrder->quantity == 0) {
                $percentage = 0;
            } else {
                $percentage = ($rawOrder->deliveredQuantity / $rawOrder->quantity) * 100;
            }

            $delivery = "($percentage %) $rawOrder->deliveredQuantity / $rawOrder->quantity KGs";
            
            $status = $rawOrder->deliveredQuantity == 0 ? 'Pending' : ($rawOrder->undeliveredQuantity > 0 ? 'Partial' : 'Completed');

            $orders[] = [
                "batch_number" => $rawOrder->batch_number,
                "coop_name" => $rawOrder->cooperative->name,
                "delivery" => $delivery,
                "status" => $status,
            ];
        }


        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('orders_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new OrderExport($orders), $file_name);
        } else {
            $columns = [
                ['name' => 'Batch No', 'key' => "batch_number"],
                ['name' => 'Cooperative', 'key' => "coop_name"], // to generate
                ['name' => 'Delivery', 'key' => "delivery"], // to generate
                ['name' => 'Status', 'key' => "status"],
            ];
            $image=$user->profile_picture;
            $imagePath = public_path('storage/' . $image); // Absolute path to image
            $data = [
                'title' => 'Orders',
                'pdf_view' => 'orders',
                'records' => $orders,
                'filename' => strtolower('orders_' . date('d_m_Y')),
                'orientation' => 'letter',
                'image' => $imagePath,
            ];
            return download_pdf($columns, $data);
        }
    }
}