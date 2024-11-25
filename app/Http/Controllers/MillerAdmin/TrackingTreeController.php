<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Collection;
use App\FinalProduct;
use App\Http\Controllers\Controller;
use App\Lot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrackingTreeController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $lots = Lot::all();

        return view("pages.miller-admin.tracking-tree", compact('lots'));
    }

    public function root_details(Request $request)
    {
       
        return response(200)->header('Content-Type', 'text/html');
    }

    public function root_identifier($root_type)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $elem = '<div class="form-group">';
        if ($root_type == 'collection') {
            $collections = Collection::all();
            $elem .= '<label for="identifier">Collection</label>
                        <select class="form-control select2bs4 node_identity" name="identifier" id="identifier">
                            <option value="">-- COLLECTION --</option>';

            foreach ($collections as $collection) {
                $elem .= "<option value='$collection->id'>$collection->collection_number</option>";
            }

            $elem .= '</select>';
        } else if ($root_type == 'lot') {
            $lots = Lot::all();
            $elem .= '<label for="identifier">Lot</label>
                        <select class="form-control select2bs4 node_identity" name="identifier" id="identifier">
                            <option value="">-- LOT --</option>';

            foreach ($lots as $lot) {
                $elem .= "<option value='$lot->lot_number'>$lot->lot_number</option>";
            }

            $elem .= '</select>';
        } else if ($root_type == 'final_product') {
            $finalProducts = FinalProduct::where("miller_id", $miller_id)->get();
            $elem .= '<label for="identifier">Final Product</label>
                        <select class="form-control select2bs4 node_identity" name="identifier" id="identifier">
                            <option value="">-- Final Product --</option>';
            foreach ($finalProducts as $finalProduct) {
                $elem .= "<option value='$finalProduct->id'>$finalProduct->product_number</option>";
            }
            $elem .= '</select>';
        } else {
            $elem .= '<label>Identifier</label>
                    <input class="form-control node_identity" name="identifier" id="identifier">
            ';
        }

        $elem .= '<span class="help-block text-danger">
                            <strong id="identifier_error"></strong>
                        </span>
                    </div>';


        return response($elem, 200)->header('Content-Type', 'text/html');
    }

    public function node_children(Request $request)
    {
        $node_type = $request->node_type;
        $node_identity = $request->node_identity;

        $direction = $request->direction;

        $elem = "";

        if ($direction == 'to_source') {
            if ($node_type == 'final_product') {
                // children are from final_product_raw_materials: milled_inventory
                $rawMaterials = DB::select(DB::raw("
                SELECT mi.id, mi.inventory_number FROM final_product_raw_materials material
                JOIN milled_inventories mi ON material.milled_inventory_id = mi.id
                WHERE material.final_product_id = :product_id
                "), ["product_id" => $node_identity]);

                foreach ($rawMaterials as $rawMaterial) {
                    $elem .= "
                <div class='node'>
                    <input type='hidden' name='node_type' class='node_type' value='milled_inventory' />
                    <input type='hidden' name='node_identity' class='node_identity' value='$rawMaterial->id' />
                    <div class='position-relative pl-3'>
                        <div class='border rounded p-3 node-card shadow-sm'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <div class='font-weight-bold text-primary'>Raw Material: Milled Inventory :$rawMaterial->inventory_number</div>
                                <button class='mt-4 ml-2 p-2 btn btn-info btn-rounded' data-toggle='collapse' data-target='#child_details'>View Details</button>
                            </div>
                            <div id='child_details'>
                                Child Details Here
                            </div>
                            <button class='btn btn-primary btn-fw btn-sm mt-2 show-children'>Show Children</button>
                            <!-- child details -->
                            <!-- /child details -->
                        </div>
                    </div>
                    <div class='pt-3 ml-3 position-relative pl-2'>
                        <div class='node_children'>
                        </div>
                    </div>
                </div>";
                }
            } else if ($node_type == 'milled_inventory') {
                // children are from pre milled inventory
                $preMilledInventories = DB::select(DB::raw("
                    SELECT pmi.id, pmi.inventory_number FROM milled_inventories mi
                    JOIN pre_milled_inventories pmi ON pmi.id = mi.pre_milled_inventory_id
                    WHERE mi.id = :milled_inv_id
                "), ["milled_inv_id" => $node_identity]);

                foreach ($preMilledInventories as $preMilledInventory) {
                    $elem .= "
                <div class='node'>
                    <input type='hidden' name='node_type' class='node_type' value='pre_milled_inventory' />
                    <input type='hidden' name='node_identity' class='node_identity' value='$preMilledInventory->id' />
                    <div class='position-relative pl-3'>
                        <div class='border rounded p-3 node-card shadow-sm'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <div class='font-weight-bold text-primary>Pre Milled Inventory :$preMilledInventory->inventory_number</div>
                                <button class='mt-4 ml-2 p-2 btn btn-info btn-rounded' data-toggle='collapse' data-target='#child_details'>View Details</button>
                            </div>
                            <div id='child_details'>
                                Child Details Here
                            </div>
                            <button class='btn btn-primary btn-fw btn-sm mt-2 show-children'>Show Children</button>
                            <!-- child details -->
                            <!-- /child details -->
                        </div>
                    </div>
                    <div class='pt-3 ml-3 position-relative pl-2'>
                        <div class='node_children'>
                        </div>
                    </div>
                </div>
                ";
                }
            } else if ($node_type == 'pre_milled_inventory') {
                // children is the delivery
                $deliveries = DB::select(DB::raw("
                    SELECT delivery.id, delivery.delivery_number FROM pre_milled_inventories pmi
                    JOIN auction_order_delivery_item item ON item.id = pmi.delivery_item_id
                    JOIN auction_order_delivery delivery ON delivery.id = item.delivery_id
                    WHERE pmi.id = :node_identity
                "), ["node_identity" => $node_identity]);

                foreach ($deliveries as $delivery) {
                    $elem .= "
                <div class='node'>
                    <input type='hidden' name='node_type' class='node_type' value='delivery' />
                    <input type='hidden' name='node_identity' class='node_identity' value='$delivery->id' />
                    <div class='position-relative pl-3'>
                        <div class='border rounded p-3 node-card shadow-sm'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <div class='font-weight-bold text-primary'>Delivery: $delivery->delivery_number</div>
                                <button class='mt-4 ml-2 p-2 btn btn-info btn-rounded' data-toggle='collapse' data-target='#child_details'>View Details</button>
                            </div>
                            <div id='child_details'>
                                Child Details Here
                            </div>
                            <button class='btn btn-primary btn-fw btn-sm mt-2 show-children'>Show Children</button>
                            <!-- child details -->
                            <!-- /child details -->
                        </div>
                    </div>
                    <div class='pt-3 ml-3 position-relative pl-2'>
                        <div class='node_children'>
                        </div>
                    </div>
                </div>
                ";
                }
            } else if ($node_type == 'delivery') {
                // children is order
                $orders = DB::select(DB::raw("
                SELECT ord.id, ord.batch_number FROM auction_order_delivery delivery
                JOIN miller_auction_order ord ON ord.id = delivery.order_id
                WHERE delivery.id = :node_identity
            "), ["node_identity" => $node_identity]);

                foreach ($orders as $order) {
                    $elem .= "
                <div class='node'>
                    <input type='hidden' name='node_type' class='node_type' value='order' />
                    <input type='hidden' name='node_identity' class='node_identity' value='$order->id' />
                    <div class='position-relative pl-3'>
                        <div class='border rounded p-3 node-card shadow-sm'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <div class='font-weight-bold text-primary'>Order: $order->batch_number</div>
                                <button class='mt-4 ml-2 p-2 btn btn-info btn-rounded' data-toggle='collapse' data-target='#child_details'>View Details</button>
                            </div>
                            <div id='child_details' class='border-top mt-1 collapse'>
                                Child Details Here
                            </div>
                            <button class='btn btn-primary btn-fw btn-sm mt-2 show-children'>Show Children</button>
                            <!-- child details -->
                            <!-- /child details -->
                        </div>
                    </div>
                    <div class='pt-3 ml-3 position-relative pl-2'>
                        <div class='node_children'>
                        </div>
                    </div>
                </div>
                ";
                }
            } else if ($node_type == 'order') {
                // children is lots
                $lots = DB::select(DB::raw("
                SELECT l.lot_number,
                    (SELECT sum(c.quantity) FROM collections c WHERE c.lot_number = l.lot_number) AS quantity
                FROM miller_auction_order_item item
                JOIN lots l ON l.lot_number = item.lot_number
                WHERE item.order_id = :node_identity
            "), ["node_identity" => $node_identity]);

                // should add grade distrubution to details?
                foreach ($lots as $lot) {
                    $elem .= "
                <div class='node'>
                    <input type='hidden' name='node_type' class='node_type' value='lot' />
                    <input type='hidden' name='node_identity' class='node_identity' value='$lot->lot_number' />
                        <div class='border rounded p-3 node-card shadow-sm'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <div class='font-weight-bold text-primary'>Lot: $lot->lot_number</div>
                                <button class='mt-4 ml-2 p-2 btn btn-info btn-rounded' data-toggle='collapse' data-target='#child_details'>View Details</button>
                            </div>
                            <div id='child_details' class='border-top mt-1 collapse'>
                                <div>Quantity: $lot->quantity KG</div>

                            </div>
                            <button class='btn btn-primary btn-fw btn-sm mt-2 show-children'>Show Children</button>
                            <!-- child details -->
                            <!-- /child details -->
                        </div>
                    </div>
                    <div class='pt-3 ml-3 position-relative pl-2'>
                        <div class='node_children'>
                        </div>
                    </div>
                </div>
                ";
                }
            } else if ($node_type == 'lot') {
                // displays collections
                $collections = DB::select(DB::raw("
                SELECT c.id,
                    c.collection_number,
                    c.quantity,
                    c.collection_time
                FROM collections c
                WHERE c.lot_number = :node_identity
            "), ["node_identity" => $node_identity]);

                $collection_times = config('enums.collection_time');

                foreach ($collections as $collection) {
                    $elem .= "
                <div class='node'>
                    <input type='hidden' name='node_type' class='node_type' value='collection' />
                    <input type='hidden' name='node_identity' class='node_identity' value='$collection->id' />
                    <div class='position-relative pl-3'>
                        <div class='border rounded p-3 node-card shadow-sm'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <div class='font-weight-bold text-primary'>Collection: $collection->collection_number</div>
                                <button class='mt-4 ml-2 p-2 btn btn-info btn-rounded' data-toggle='collapse' data-target='#child_details'>View Details</button>
                            </div>
                            <div id='child_details' class='border-top mt-1 collapse'>
                                <div>Quantity: $collection->quantity KG </div>
                                <div>Collection Time: {$collection_times[$collection->collection_time]} </div>
                            </div>
                            <!--  <button class='btn btn-primary btn-fw btn-sm mt-2 show-children'>Show Children</button> -->
                            <!-- child details -->
                            <!-- /child details -->
                        </div>
                    </div>
                    <div class='pt-3 ml-3 position-relative pl-2'>
                        <div class='node_children'>
                        </div>
                    </div>
                </div>
                ";
                }
            }
        } else {
            if ($node_type == 'collection') {
                // children is lots
                $lots = DB::select(DB::raw("
                    SELECT l.lot_number,
                        (SELECT sum(c.quantity) FROM collections c WHERE c.lot_number = l.lot_number) AS quantity
                    FROM collections c
                    JOIN lots l ON l.lot_number = c.lot_number
                    WHERE c.id = :node_identity
                "), ["node_identity" => $node_identity]);


                // should add grade distrubution to details?
                foreach ($lots as $lot) {
                    $elem .= "
                <div class='node'>
                    <input type='hidden' name='node_type' class='node_type' value='lot' />
                    <input type='hidden' name='node_identity' class='node_identity' value='$lot->lot_number' />
                    <div class='position-relative pl-3'>
                        <div class='border rounded p-3 node-card shadow-sm'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <div class='font-weight-bold text-primary'>Lot:  $lot->lot_number </div>
                                <button class='mt-4 ml-2 p-2 btn btn-info btn-rounded' data-toggle='collapse' data-target='#child_details'>View Details</button>
                            </div>
                            <div id='child_details' class='border-top mt-2 collapse'>
                                <div class='pt-2'>
                                    <span class='font-weight-bold'>Quantity:</span> $lot->quantity  KG
                                </div>
                            </div>
                            <button class='btn btn-primary btn-fw btn-sm mt-2 show-children'>Show Children</button>
                        </div>
                    </div>
                    <div class='pt-3 ml-3 position-relative pl-2'>
                        <div class='node_children'>
                        </div>
                    </div>
                </div>
                ";
                }
            } else if ($node_type == 'lot') {
                // children is order
                $orders = DB::select(DB::raw("
                    SELECT ord.id, ord.batch_number FROM lots l
                    JOIN miller_auction_order_item item ON item.lot_number = l.lot_number
                    JOIN miller_auction_order ord ON ord.id = item.order_id
                    WHERE l.lot_number = :node_identity
                "), ["node_identity" => $node_identity]);

                foreach ($orders as $order) {
                    $elem .= "
                <div class='node'>
                    <input type='hidden' name='node_type' class='node_type' value='order' />
                    <input type='hidden' name='node_identity' class='node_identity' value='$order->id' />
                    <div class='position-relative pl-3'>
                        <div class='border rounded p-3 node-card shadow-sm'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <div class='font-weight-bold text-primary'>Order: $order->batch_number </div>
                                <button class='mt-4 ml-2 p-2 btn btn-info btn-rounded' data-toggle='collapse' data-target='#child_details'>View Details</button>
                            </div>
                            <div id='child_details' class='border-top mt-1 collapse'>
                                Child Details Here
                            </div>
                            <button class='btn btn-primary btn-fw btn-sm  mt-2 show-children'>Show Children</button>
                            <!-- child details -->
                            <!-- /child details -->
                        </div>
                    </div>
                    <div class='pt-3 ml-3 position-relative pl-2'>
                        <div class='node_children'>
                        </div>
                    </div>
                </div>
                ";
                }
            } else if ($node_type == 'order') {
                // children is the delivery
                $deliveries = DB::select(DB::raw("
                SELECT delivery.id, delivery.delivery_number FROM miller_auction_order ord
                JOIN auction_order_delivery delivery ON delivery.order_id = ord.id
                WHERE ord.id = :node_identity
            "), ["node_identity" => $node_identity]);

                foreach ($deliveries as $delivery) {
                    $elem .= "
                <div class='node'>
                    <input type='hidden' name='node_type' class='node_type' value='delivery' />
                    <input type='hidden' name='node_identity' class='node_identity' value='$delivery->id' />
                    <div class='position-relative pl-3'>
                        <div class='border rounded p-3 node-card shadow-sm'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <div class='font-weight-bold text-primary'>Delivery: $delivery->delivery_number</div>
                                <button class='mt-4 ml-2 p-2 btn btn-info btn-rounded' data-toggle='collapse' data-target='#child_details'>View Details</button>
                            </div>
                            <div id='child_details'>
                                Child Details Here
                            </div>
                            <button class='btn btn-primary btn-fw btn-sm  mt-2 show-children'>Show Children</button>
                            <!-- child details -->
                            <!-- /child details -->
                        </div>
                    </div>
                    <div class='pt-3 ml-3 position-relative pl-2'>
                        <div class='node_children'>
                        </div>
                    </div>
                </div>
                ";
                }
            } else if ($node_type == 'delivery') {
                // children are from pre milled inventory
                $preMilledInventories = DB::select(DB::raw("
                    SELECT pmi.id, pmi.inventory_number FROM auction_order_delivery delivery
                    JOIN pre_milled_inventories pmi ON pmi.delivery_id = delivery.id
                    WHERE delivery.id = :node_identity
                "), ["node_identity" => $node_identity]);

                foreach ($preMilledInventories as $preMilledInventory) {
                    $elem .= "
                <div class='node'>
                    <input type='hidden' name='node_type' class='node_type' value='pre_milled_inventory' />
                    <input type='hidden' name='node_identity' class='node_identity' value='$preMilledInventory->id' />
                    <div class='position-relative pl-3'>
                        <div class='border rounded p-3 node-card shadow-sm'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <div class='font-weight-bold text-primary'>Pre Milled Inventory :$preMilledInventory->inventory_number</div>
                                <button class='mt-4 ml-2 p-2 btn btn-info btn-rounded' data-toggle='collapse' data-target='#child_details'>View Details</button>
                            </div>
                            <div id='child_details'>
                                Child Details Here
                            </div>
                            <button class='btn btn-primary btn-fw btn-sm  mt-2 show-children'>Show Children</button>
                            <!-- child details -->
                            <!-- /child details -->
                        </div>
                    </div>
                    <div class='pt-3 ml-3 position-relative pl-2'>
                        <div class='node_children'>
                        </div>
                    </div>
                </div>
                ";
                }
            } else if ($node_type == 'pre_milled_inventory') {
                // children are from final_product_raw_materials: milled_inventory
                $rawMaterials = DB::select(DB::raw("
                SELECT mi.id, mi.inventory_number FROM pre_milled_inventories pmi
                JOIN milled_inventories mi ON mi.pre_milled_inventory_id = pmi.id
                WHERE pmi.id = :node_identity
                "), ["node_identity" => $node_identity]);

                foreach ($rawMaterials as $rawMaterial) {
                    $elem .= "
                <div class='node'>
                    <input type='hidden' name='node_type' class='node_type' value='milled_inventory' />
                    <input type='hidden' name='node_identity' class='node_identity' value='$rawMaterial->id' />
                    <div class='position-relative pl-3'>
                        <div class='border rounded p-3 node-card shadow-sm'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <div class='font-weight-bold text-primary'>Raw Material: Milled Inventory :$rawMaterial->inventory_number</div>
                                <button class='mt-4 ml-2 p-2 btn btn-info btn-rounded' data-toggle='collapse' data-target='#child_details'>View Details</button>
                            </div>
                            <div id='child_details'>
                                Child Details Here
                            </div>
                            <button class='btn btn-primary btn-fw btn-sm  mt-2 show-children'>Show Children</button>
                            <!-- child details -->
                            <!-- /child details -->
                        </div>
                    </div>
                    <div class='pt-3 ml-3 position-relative pl-2'>
                        <div class='node_children'>
                        </div>
                    </div>
                </div>";
                }
            } else if ($node_type == 'milled_inventory') {
                // children are final_products
                $finalProducts = DB::select(DB::raw("
                SELECT p.id, p.product_number FROM milled_inventories mi
                JOIN final_product_raw_materials material ON material.milled_inventory_id = mi.id
                JOIN final_products p ON p.id = material.final_product_id
                WHERE mi.id = :node_identity
                "), ["node_identity" => $node_identity]);

                foreach ($finalProducts as $finalProduct) {
                    $elem .= "
                <div class='node'>
                    <input type='hidden' name='node_type' class='node_type' value='final_product' />
                    <input type='hidden' name='node_identity' class='node_identity' value='$finalProduct->id' />
                    <div class='position-relative pl-3'>
                        <div class='border rounded p-2'>
                            <div class='d-flex justify-content-between'>
                                <div>Product :$finalProduct->product_number</div>
                                <button class='mt-4 ml-2 p-2 btn btn-info btn-rounded' data-toggle='collapse' data-target='#child_details'>View Details</button>
                            </div>
                            <div id='child_details'>
                                Child Details Here
                            </div>
                            <button class='btn btn-primary btn-fw btn-sm  mt-2 show-children'>Show Children</button>
                            <!-- child details -->
                            <!-- /child details -->
                        </div>
                    </div>
                    <div class='pt-3 ml-3 position-relative pl-2'>
                        <div class='node_children'>
                        </div>
                    </div>
                </div>";
                }
            }
        }

        return response($elem, 200)->header('Content-Type', 'text/html');
    }
}
