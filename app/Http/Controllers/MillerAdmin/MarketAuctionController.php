<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Collection;
use App\Cooperative;
use App\Http\Controllers\Controller;
use App\Lot;
use App\MillerAuctionCart;
use App\MillerAuctionCartItem;
use App\MillerAuctionOrder;
use App\MillerAuctionOrderItem;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Product;
use Log;
use Illuminate\Support\Facades\Http;

class MarketAuctionController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $cooperatives = DB::select(DB::raw("
            SELECT coop.*,
                (SELECT count(1) FROM lots l
                    WHERE l.cooperative_id = coop.id AND
                    (SELECT count(1) FROM miller_auction_order_item item
                        WHERE item.lot_number = l.lot_number
                    ) = 0
                ) AS lots_count
            FROM cooperatives coop
        "));

        $totalLots = DB::select(DB::raw("
            SELECT SUM(
                (SELECT count(1) FROM lots l
                    WHERE l.cooperative_id = coop.id AND
                    (SELECT count(1) FROM miller_auction_order_item item
                        WHERE item.lot_number = l.lot_number
                    ) = 0
                )
            ) AS total_lots
            FROM cooperatives coop
        "));

        $totalLots = $totalLots[0]->total_lots ?? 0;

        return view('pages.miller-admin.market-auction.index', compact('cooperatives', 'totalLots'));
    }


    public function view_coop_collections($coop_id)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }
    
        // Update or create cart
        $cart = null;
        try {
            $cart = MillerAuctionCart::where("miller_id", $miller_id)
                ->where("cooperative_id", $coop_id)
                ->where("user_id", $user->id)
                ->first();
            if ($cart == null) {
                throw new \Throwable("cart does not exist");
            }
        } catch (\Throwable $th) {
            try {
                $cart = new MillerAuctionCart();
                $cart->miller_id = $miller_id;
                $cart->cooperative_id = $coop_id;
                $cart->user_id = $user->id;
                $cart->save();
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                DB::rollBack();
                toastr()->error('Unable to initialize cart');
                return redirect()->back();
            }
        }
    
        // Fetch lots with quantities
        $lots = DB::select(DB::raw("
            SELECT l.*, item.quantity AS qty
            FROM lots l
            LEFT JOIN miller_auction_cart_item item ON item.lot_number = l.lot_number
            LEFT JOIN miller_auction_cart cart ON cart.id = item.cart_id
            WHERE l.cooperative_id = :coop_id AND 
                (SELECT count(1) FROM miller_auction_order_item item
                    WHERE item.lot_number = l.lot_number
                ) = 0
        "), ["coop_id" => $coop_id]);
    
        // Count all cooperative lots except those in an order
        $lots_count = DB::select(DB::raw("
            SELECT count(1) AS count
            FROM lots l
            WHERE l.cooperative_id = :coop_id AND
                (SELECT count(1) FROM miller_auction_order_item item
                    WHERE item.lot_number = l.lot_number
                ) = 0
        "), ["coop_id" => $coop_id])[0]->count;
    
        // Calculate total quantity of available lots
        $total_quantity = DB::select(DB::raw("
            SELECT SUM(l.available_quantity) AS total_quantity
            FROM lots l
            WHERE l.cooperative_id = :coop_id AND
                (SELECT count(1) FROM miller_auction_order_item item
                    WHERE item.lot_number = l.lot_number
                ) = 0
        "), ["coop_id" => $coop_id])[0]->total_quantity;
    
        // Fetch cooperative details
        $cooperative = null;
        $cooperatives = DB::select(DB::raw("
            SELECT * FROM cooperatives WHERE id = :coop_id;
        "), ["coop_id" => $coop_id]);
        if (count($cooperatives) > 0) {
            $cooperative = $cooperatives[0];
        }
    
        // Count items in cart
        $items_in_cart_count = DB::select(DB::raw("
            SELECT count(1) AS count
            FROM miller_auction_cart_item item
            WHERE cart_id = :cart_id
        "), ["cart_id" => $cart->id])[0]->count;
    
        return view('pages.miller-admin.market-auction.coop-lots', compact('cooperative', 'lots', 'lots_count', 'items_in_cart_count', 'total_quantity'));
    }
    

    // todo: replace with remove lot from cart
    public function remove_collection_from_cart($coop_id, $collection_id)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $cart = MillerAuctionCart::where("miller_id", $miller_id)
            ->where("cooperative_id", $coop_id)
            ->where("user_id", $user->id)
            ->first();

        // check item exists
        $cartItem = MillerAuctionCartItem::where("cart_id", $cart->id)
            ->where("collection_id", $collection_id);

        if (is_null($cartItem)) {
            toastr()->error('Item not in cart');
            return redirect()->back();
        }

        $cartItem->delete();


        toastr()->success('Removed item to cart');
        return redirect()->route("miller-admin.market-auction.coop-collections.show", $coop_id);
    }

    public function view_checkout_cart($coop_id)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }
    
        // cart
        $cart = MillerAuctionCart::where("miller_id", $miller_id)
            ->where("cooperative_id", $coop_id)
            ->where("user_id", $user->id)
            ->first();
    
        if (!$cart) {
            // Handle case where no cart is found, e.g., return an error or redirect
            return redirect()->back()->with('error', 'Cart not found.');
        }
    
        // total in cart
        $totalInCart = DB::select(DB::raw("
            SELECT sum(item.quantity) AS total
            FROM miller_auction_cart_item item
            WHERE item.cart_id = :cart_id
        "), ["cart_id" => $cart->id])[0]->total;
    
        // aggregate grade distribution
        $aggregateGradeDistribution = DB::select(DB::raw("
            SELECT SUM(d.quantity) AS total,
                pg.name AS grade
            FROM lot_grade_distributions d
            JOIN product_grades pg ON pg.id = d.product_grade_id
            JOIN lots l ON l.lot_number = d.lot_number
            JOIN miller_auction_cart_item item ON item.lot_number = l.lot_number
            WHERE item.cart_id = :cart_id
            GROUP BY d.product_grade_id
            ORDER BY total DESC
        "), ["cart_id" => $cart->id]);
    
        // cart items
        $cartItems = DB::select(DB::raw("
            SELECT item.*
            FROM miller_auction_cart_item item
            JOIN miller_auction_cart cart ON cart.id = item.cart_id AND cart.id = :cart_id;
        "), ["cart_id" => $cart->id]);
    
        foreach ($cartItems as $item) {
            $item->distributions = DB::select(DB::raw("
                SELECT sum(distribution.quantity) AS total,
                    (SELECT name FROM product_grades pd WHERE pd.id = distribution.product_grade_id ) AS grade
                FROM lot_grade_distributions distribution
                JOIN lots l ON l.lot_number=distribution.lot_number AND l.lot_number=:lot_number
                GROUP BY distribution.product_grade_id
                ORDER BY total DESC
            "), ["lot_number" => $item->lot_number]);
        }
    
        // cooperative
        $cooperative = null;
        $cooperatives = DB::select(DB::raw("
            SELECT * FROM cooperatives WHERE id = :coop_id;
        "), ["coop_id" => $coop_id]);
        if (count($cooperatives) > 0) {
            $cooperative = $cooperatives[0];
        }
    
        // default batch number
        $suffix_batch_number = MillerAuctionOrder::count() + 1;
        $now = Carbon::now();
        $now_str = strtoupper($now->format('Ymd'));
        $default_batch_number = "B$now_str-$suffix_batch_number";
    
        // warehouses
        $warehouses = DB::select(DB::raw("
            SELECT * FROM miller_warehouse WHERE miller_id = :miller_id
        "), ["miller_id" => $miller_id]);
    
        return view('pages.miller-admin.market-auction.cart-checkout', compact('cooperative', 'cartItems', 'warehouses', 'totalInCart', 'aggregateGradeDistribution', 'default_batch_number'));
    }
    
    // todo: implement
    public function clear_cart($coop_id)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        try {
            MillerAuctionCart::where("miller_id", $miller_id)
                ->where("cooperative_id", $coop_id)
                ->where("user_id", $user->id)
                ->delete();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Unable to clear cart');
            return redirect()->back();
        }


        toastr()->success('Cart cleared successfully');
        return redirect()->route("miller-admin.market-auction.coop-collections.show", $coop_id);
    }



    // creates order
    public function checkout_cart(Request $request, $coop_id)
    {
        $request->validate([
            "batch_number" => "required|unique:miller_auction_order,batch_number",
            "miller_warehouse_id" => "required|exists:miller_warehouse,id",
        ]);

        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $cart = MillerAuctionCart::where("miller_id", $miller_id)
            ->where("cooperative_id", $coop_id)
            ->where("user_id", $user->id)
            ->first();

        $cartItemsCount = $cart->items->count();
        if ($cartItemsCount <= 0) {
            toastr()->error('Cart is empty');
            return redirect()->back();
        }

        // start transaction
        DB::beginTransaction();

        // create order
        try {
            $order = new MillerAuctionOrder();
            $order->batch_number = $request->batch_number;
            $order->miller_id = $miller_id;
            $order->miller_warehouse_id = $request->miller_warehouse_id;
            $order->cooperative_id = $coop_id;
            $order->user_id = $user->id;
            $order->save();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to initialize cart');
            return redirect()->back();
        }

        // retrieve collections in cart
        $lotsInCart = DB::select(DB::raw("
            SELECT item.lot_number, item.quantity FROM miller_auction_cart_item item
            WHERE item.cart_id = :cart_id
        "), ["cart_id" => $cart->id]);
        // Initialize an array to collect lot numbers
    $lotNumbers = [];

        foreach ($lotsInCart as $item) {
            try {
                $lot_number = $item->lot_number;

                $orderItem = new MillerAuctionOrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->lot_number = $lot_number;
                $orderItem->quantity = $item->quantity;
                $orderItem->save();
            // Add the lot number to the array
            $lotNumbers[] = $lot_number;
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                DB::rollBack();
                toastr()->error('Unable to create order item');
                return redirect()->back();
            }
        }

        // cart is no longer needed delete it
        $cart->delete();

        // commit changes
       // Debugging: log order and order items before committing
//Log::debug('Checkout Order Data: ' . json_encode([
    //'order' => $order->toArray(),
    //'order_items' => $orderItem
//], JSON_PRETTY_PRINT));

        DB::commit();
        toastr()->success('Order created successfully');

        // Auto-run private function after DB commit
    //return $this->processBatchAndLots($order, $lotNumbers, $cart);

        // redirect to orders
        return redirect()->route("miller-admin.orders.show");

    }

    public function get_or_create_cart_item($coop_id, $lot_number): MillerAuctionCartItem
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }
        // update or create cart
        $cart = null;
        try {
            $cart = MillerAuctionCart::where("miller_id", $miller_id)
                ->where("cooperative_id", $coop_id)
                ->where("user_id", $user->id)
                ->first();
            if ($cart == null) {
                throw new \Throwable("cart does not exist");
            }
        } catch (\Throwable $th) {
            try {
                $cart = new MillerAuctionCart();
                $cart->miller_id = $miller_id;
                $cart->cooperative_id = $coop_id;
                $cart->user_id = $user->id;
                $cart->save();
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                DB::rollBack();
                toastr()->error('Unable to initialize cart');
                return redirect()->back();
            }
        }

        try {
            $cartItem = MillerAuctionCartItem::where("cart_id", $cart->id)
                ->where("lot_number", $lot_number)
                ->firstOrFail();
        } catch (\Throwable $th) {
            //throw $th;
            $cartItem = new MillerAuctionCartItem();
            $cartItem->cart_id = $cart->id;
            $cartItem->lot_number = $lot_number;
            $cartItem->quantity = 0;
            $cartItem->save();
        }

        return $cartItem;
    }


    public function add_lot_to_cart_new(Request $request, $coop_id, $lot_id)
    {
        $quantity = $request->query('quantity');
        if (!$quantity || $quantity < 1) {
            return redirect()->back()->with('error', 'Invalid quantity.');
        } 

        $coop_id=$coop_id;
        $lot_number=$lot_id;
        $quantity=$request->quantity;

        //dd($coop_id,$lot_number,$quantity);

        DB::beginTransaction();
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        // retrieve lot
        try {
            $lot = Lot::where("cooperative_id", $coop_id)
                ->where("lot_number", $lot_number)
                ->firstOrFail();
               // dd($lot); 
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error('Lot does not exist');
            return redirect()->back();
        }

        // update or create cart
        $cart = null;
        try {
            $cart = MillerAuctionCart::where("miller_id", $miller_id)
                ->where("cooperative_id", $coop_id)
                ->where("user_id", $user->id)
                ->first();
            if ($cart == null) {
                throw new \Throwable("cart does not exist");
            }
        } catch (\Throwable $th) {
            try {
                $cart = new MillerAuctionCart();
                $cart->miller_id = $miller_id;
                $cart->cooperative_id = $coop_id;
                $cart->user_id = $user->id;
                $cart->save();
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                DB::rollBack();
                toastr()->error('Unable to initialize cart');
                return redirect()->back();
            }
        }

        try {
            $cartItem = MillerAuctionCartItem::where("cart_id", $cart->id)
                ->where("lot_number", $lot_number)
                ->firstOrFail();
        } catch (\Throwable $th) {
            //throw $th;
            $cartItem = new MillerAuctionCartItem();
            $cartItem->cart_id = $cart->id;
            $cartItem->lot_number = $lot_number;
            $cartItem->quantity = $quantity;
            $cartItem->save();
        }       
        //update lot available quantity
        $lot->available_quantity=$lot->available_quantity-$quantity;
        $lot->save();

        DB::commit();

        toastr()->success('Item added to cart successfully');
        return redirect()->back();
    }


    public function add_lot_to_cart($coop_id, $lot_number)
    {
        DB::beginTransaction();
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        // retrieve lot
        try {
            $lot = Lot::where("cooperative_id", $coop_id)
                ->where("lot_number", $lot_number)
                ->firstOrFail();
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error('Lot does not exist');
            return redirect()->back();
        }

        // update or create cart
        $cart = null;
        try {
            $cart = MillerAuctionCart::where("miller_id", $miller_id)
                ->where("cooperative_id", $coop_id)
                ->where("user_id", $user->id)
                ->first();
            if ($cart == null) {
                throw new \Throwable("cart does not exist");
            }
        } catch (\Throwable $th) {
            try {
                $cart = new MillerAuctionCart();
                $cart->miller_id = $miller_id;
                $cart->cooperative_id = $coop_id;
                $cart->user_id = $user->id;
                $cart->save();
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                DB::rollBack();
                toastr()->error('Unable to initialize cart');
                return redirect()->back();
            }
        }

        try {
            $cartItem = MillerAuctionCartItem::where("cart_id", $cart->id)
                ->where("lot_number", $lot_number)
                ->firstOrFail();
        } catch (\Throwable $th) {
            //throw $th;
            $cartItem = new MillerAuctionCartItem();
            $cartItem->cart_id = $cart->id;
            $cartItem->lot_number = $lot_number;
            $cartItem->quantity = $lot->available_quantity;
            $cartItem->save();
        }

        DB::commit();

        toastr()->success('Item added to cart successfully');
        return redirect()->back();
    }

    public function remove_lot_from_cart($coop_id, $lot_number, Request $request)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        DB::beginTransaction();

        $cart = null;
        try {
            $cart = MillerAuctionCart::where("miller_id", $miller_id)
                ->where("cooperative_id", $coop_id)
                ->where("user_id", $user->id)
                ->first();
            if ($cart == null) {
                throw new \Throwable("cart does not exist");
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error('Cart does not exist');
            return redirect()->back();
        }

        try {
            $cartItem = MillerAuctionCartItem::where("cart_id", $cart->id)
                ->where("lot_number", $lot_number)
                ->firstOrFail();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            toastr()->error('Item does not exist');
            return redirect()->back();
        }
        // retrieve lot
        try {
            $lot = Lot::where("cooperative_id", $coop_id)
                ->where("lot_number", $lot_number)
                ->firstOrFail(); 
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error('Lot does not exist');
            return redirect()->back();
        }
        //update lot available quantity
        $lot->available_quantity=$lot->available_quantity+$cartItem->quantity;
        $lot->save();
        
        $cartItem->delete();

        DB::commit();
        toastr()->success('Cart updated successfully');
        return redirect()->back();
    }

    /**
 * Private function to process batch, lots, and collections after DB commit
 *
 * @param MillerAuctionOrder $order
 * @param array $lotNumbers
 * @param MillerAuctionCart $cart
 * @return \Illuminate\Http\JsonResponse
 */
private function processBatchAndLots($order, $lotNumbers, $cart)
{
    // Extract batch number and lots, query collections
    $batch_number = $order->batch_number;
    $lots = [];
    $productNames = []; // Array to store unique product names

    foreach ($lotNumbers as $lotNumber) {
        // Query collections based on lot number
        $collections = Collection::where('lot_number', $lotNumber)
            ->select('collection_number', 'quantity', 'product_id') // Include product_id in the query
            ->get();

        // Add lot and its collections to the response array
        $lots[] = [
            'lot_number' => $lotNumber,
            'collections' => $collections->map(function ($collection) use (&$productNames) {
                // Query the product name for the current collection's product_id
                $productName = Product::where('id', $collection->product_id)->value('name');
                
                // Add the product name to the array if it doesn't already exist
                if (!in_array($productName, $productNames)) {
                    $productNames[] = $productName;
                }

                return [
                    'collection_number' => $collection->collection_number,
                    'collection_quantity' => $collection->quantity,
                ];
            })->toArray() // Ensure collections is an array
        ];
    }

    // Convert product names to a comma-separated string
    $product_name_string = implode(', ', $productNames);

    // Create final JSON response
    $response = [
        "data" => [
            "batch_number" => $batch_number,
            "lots" => $lots,
            "product_name" => $product_name_string
        ]
    ];

    // Call saveToBlockchain function
    $this->saveToBlockchain($response);
    return redirect()->route('miller-admin.orders.show');
    
}

private function saveToBlockchain($response)
{
    // Define constants
    $baseUrl = env('BLOCKCHAIN_API_URL'); // Base URL from .env file

    // Helper function to get the token
    function getToken()
    {
        global $encryptedPrivateKey, $tokenFile;
        $encryptedPrivateKey = env('ENCRYPTED_PRIVATE_KEY'); // Encrypted private key from .env file
        $tokenFile = storage_path('token.json'); // Path to store the token
        
        // Load URL from .env and ensure it's valid
        $baseUrl = rtrim(env('BLOCKCHAIN_API_URL'), '/');
    
        if (!filter_var($baseUrl, FILTER_VALIDATE_URL)) {
            Log::error("Invalid BASE URL for blockchain API: $baseUrl");
            throw new \Exception('Invalid base URL for blockchain API');
        }
    
        // Function to request a new token
       
    
        function requestNewToken($baseUrl, $encryptedPrivateKey)
{
    $response = Http::post("{$baseUrl}/auth/token", [
        'encrypted_private_key' => $encryptedPrivateKey
    ]);

    if ($response->failed()) {
        Log::error('Failed to fetch new token: ' . $response->body());
        throw new \Exception('Failed to fetch new token');
    }

    $responseData = $response->json();
    // Normalize keys to lowercase
    $responseData = array_change_key_case($responseData, CASE_LOWER);

    // Check if 'data' key exists
    if (!isset($responseData['data'])) {
        Log::error('No data key in token response: ' . json_encode($responseData));
        throw new \Exception('No data key in token response');
    }

    $tokenData = $responseData['data'];

    // Check if expires_in key exists
    if (!isset($tokenData['expires_in'])) {
        Log::error('No expires_in key in token data: ' . json_encode($tokenData));
        throw new \Exception('No expires_in key in token data');
    }

    // Set expiration time for the token
    $tokenData['expires_in'] = time() + $tokenData['expires_in'] - 60; // Refresh before expiration

    return $tokenData;
}

// Check if token file exists and is not empty
if (file_exists($tokenFile) && filesize($tokenFile) > 0) {
    $tokenData = json_decode(file_get_contents($tokenFile), true);
    
    // Check if the token data is valid and contains the necessary keys
    if (isset($tokenData['expires_in']) && time() > $tokenData['expires_in']) {
        // Token expired, delete the file and fetch a new token
        unlink($tokenFile);
        $tokenData = requestNewToken($baseUrl, $encryptedPrivateKey);
        file_put_contents($tokenFile, json_encode($tokenData));
    }
    
    return $tokenData['token']; // Access the token correctly
}

    }

    $token = getToken();





    // Prepare the data in the required format
$formattedData = [
    'data' => [
        'batch_number' => $response['data']['batch_number'],
        'lots' => array_map(function ($lot) {
            // Remove 'Illuminate\\Support\\Collection' wrapping
            if (isset($lot['collections']) && isset($lot['collections']['Illuminate\\Support\\Collection'])) {
                $lot['collections'] = $lot['collections']['Illuminate\\Support\\Collection'];
            }

            // Ensure that collection_quantity is a float
            $lot['collections'] = array_map(function ($collection) {
                if (isset($collection['collection_quantity']) && is_string($collection['collection_quantity'])) {
                    $collection['collection_quantity'] = (float) $collection['collection_quantity'];
                }
                return $collection;
            }, $lot['collections']);

            return $lot;
        }, $response['data']['lots']),
        'product_name' => $response['data']['product_name']
    ],
    'file_memo' => 'Farm Collections saved successfully',
    'file_private_key' => env('ENCRYPTED_PRIVATE_KEY')
];

   Log::debug("Final data to send:",$formattedData);
   // Load URL from .env and ensure it's valid
   $baseUrl = rtrim(env('BLOCKCHAIN_API_URL'), '/');
    // Send data to blockchain
    $blockchainResponse = Http::withToken($token)->post("{$baseUrl}/files/create", $formattedData);

    // Handle response or log errors
    if ($blockchainResponse->failed()) {
        Log::error('Failed to save to blockchain: ' . $blockchainResponse->body());
        return;
    }

    // Extract the file ID from the blockchain response
    $blockchainData = array_change_key_case($blockchainResponse->json(), CASE_LOWER);

    if (isset($blockchainData['data']['file_id'])) {
        $fileId = $blockchainData['data']['file_id'];
    } else {
        Log::error('No file_id found in blockchain response');
        $fileId = null; // Handle case where file_id is not found
    }

    // Append the file ID to the original response
    $finalResponse = array_merge($response, [
        'file_id' => $fileId
    ]);
    $blockDataFile = storage_path('blockdata.json');

// Check if the file already exists and has content
if (file_exists($blockDataFile) && filesize($blockDataFile) > 0) {
    // Read the existing content
    $existingData = json_decode(file_get_contents($blockDataFile), true);

    // Ensure the existing data is an array
    if (!is_array($existingData)) {
        $existingData = [];
    }
} else {
    // If the file does not exist or is empty, initialize an empty array
    $existingData = [];
}

// Append the new final response to the existing data
$existingData[] = $finalResponse;

// Save the updated data back to blockdata.json
file_put_contents($blockDataFile, json_encode($existingData, JSON_PRETTY_PRINT));

    Log::info('Successfully saved to blockchain and updated blockdata.json');
    // Redirect the user back to the desired route after saving

}

}
