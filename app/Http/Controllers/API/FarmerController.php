<?php

namespace App\Http\Controllers\API;

use App\Collection;
use App\Farmer;
use App\Route;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Cooperative;
use MongoDB\Driver\Exception\Exception;

class FarmerController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    public function routes(): \Illuminate\Http\JsonResponse
    {
        try {
            // Fetch all routes
            $routes = Route::all();
    
            // Transform routes data
            $formattedRoutes = $routes->map(function ($route) {
                // Fetch cooperative name corresponding to cooperative_id
                $cooperativeName = Cooperative::find($route->cooperative_id)->name ?? null;
    
                // Return formatted route data
                return [
                    "id" => $route->id,
                    "name" => $route->name,
                    "cooperative_name" => $cooperativeName
                ];
            });
    
            // Return formatted response
            return response()->json($formattedRoutes);
        } catch (\Exception $e) {
            // Log and return error response
            Log::error('An error occurred in the FarmerController@routes method: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred...check the logs'], 500);
        }
    }

    
    public function farmers(Request $request, int $limit=null): \Illuminate\Http\JsonResponse
    {
        try {
           
            
            
            
            // Get the custom limit from the request, or default to null
            $limit = $request->input('limit');
    
            // Fetch farmers data with related models loaded
            $query = Farmer::with('country', 'location', 'bank_branch', 'route', 'user.cooperative');
    
            // Check if a custom limit is provided
            if ($limit !== null && is_numeric($limit)) {
                $farmers = $query->limit($limit)->get();
            } else {
                // No custom limit provided, return all results
                $farmers = $query->get();
            }
    
            // Transform data
            $formattedFarmers = $farmers->map(function ($farmer) {
                return [
                    'id' => $farmer->id,
                    'first_name' => $farmer->user->first_name ?? null,
                    'other_names' => $farmer->user->other_names ?? null,
                    'country_name' => $farmer->country->name ?? null,
                    'location_name' => $farmer->location->name ?? null,
                    'county' => $farmer->county,
                    'masked_id_no' => strlen($farmer->id_no) > 4 ? substr($farmer->id_no, 0, 2) . str_repeat('*', strlen($farmer->id_no) - 4) . substr($farmer->id_no, -2) : str_repeat('*', strlen($farmer->id_no)),
                    'masked_phone_no' => strlen($farmer->phone_no) > 6 ? substr($farmer->phone_no, 0, 4) . str_repeat('*', strlen($farmer->phone_no) - 6) . substr($farmer->phone_no, -2) : str_repeat('*', strlen($farmer->phone_no)),
                    'phone_no_hashed' => hash('sha256', $farmer->phone_no),
                    'cooperative_name' => $farmer->user->cooperative->name ?? null,
                    'route_name' => $farmer->route->name ?? null,
                    'bank_account' => $farmer->bank_account,
                    'member_no' => $farmer->member_no,
                    'customer_type' => $farmer->customer_type,
                    'kra' => $farmer->kra,
                    'farm_size' => $farmer->farm_size,
                    'age' => $farmer->age,
                    'dob' => $farmer->dob,
                    'gender' => $farmer->gender,
                    'bank_branch_name' => $farmer->bank_branch->name ?? null,
                ];
                
                
                
            });
    
            // Return formatted response
            return response()->json([
                "success" => true,
                "data" => $formattedFarmers
            ], 200);
        } catch (\Exception $e) {
            $environment = app()->environment();
            if ($environment === 'production') {
                Log::channel('prod')->error($e->getMessage());
                return response()->json([
                    "success" => false,
                    "error" => "An error occurred while processing your request. Please try again later"
                ], 500);
            } else {
                // Return error response with code 500
                return response()->json([
                    "success" => false,
                    "error" => $e->getMessage()
                ], 500);
            }
        }
    }


    public function list_collections(Request $request):\Illuminate\Http\JsonResponse
{
    try {
        // Build the query with basic eager loading
        $query = Collection::with(['farmer.user', 'cooperative', 'product', 'agent', 'collection_quality_standard']);

        // Apply filters based on request parameters
        $filters = $request->all(); // Get all request parameters

        // Filter by cooperative
        if (isset($filters['cooperative_name'])) {
            $query->whereHas('cooperative', function ($cooperativeQuery) use ($filters) {
                $cooperativeQuery->where('name', $filters['cooperative_name']);
            });
        }

        // Filter by farmer (assuming farmer_id exists)
        if (isset($filters['farmer_id'])) {
            $query->where('farmer_id', $filters['farmer_id']);
        }

        // Fetch filtered collections with pagination
        $collections = $query->paginate(1000); // Adjust the number based on your needs

        // Transform the collections data
        $formattedCollections = $collections->map(function ($collection) {
            // Retrieve agent's name using agent_id
            $agentName = optional($collection->agent)->first_name . ' ' . optional($collection->agent)->other_names;

            // Retrieve collection quality standard name
            $collectionQualityStandardName = optional($collection->collection_quality_standard)->name;

            return [
                'id' => $collection->id,
                'farmer_id' => $collection->farmer_id,
                'farmer_name' => optional($collection->farmer->user)->first_name . ' ' . optional($collection->farmer->user)->other_names,
                'cooperative_id' => $collection->cooperative_id,
                'cooperative_name' => optional($collection->cooperative)->name,
                'product_id' => $collection->product_id,
                'product_name' => optional($collection->product)->name,
                'agent_id' => $collection->agent_id,
                'agent_name' => $agentName,
                'quantity' => $collection->quantity,
                'status' => $collection->status,
                'submission_status' => $collection->submission_status,
                'date_collected' => $collection->date_collected,
                'comments' => $collection->comments,
                'collection_time' => $collection->collection_time,
                'created_at' => $collection->created_at,
                'updated_at' => $collection->updated_at,
                'deleted_at' => $collection->deleted_at,
                'collection_number' => $collection->collection_number,
                'batch_no' => $collection->batch_no,
                'available_quantity' => $collection->available_quantity,
                'collection_quality_standard_id' => $collection->collection_quality_standard_id,
                'collection_quality_standard_name' => $collectionQualityStandardName,
                'unit_price' => $collection->unit_price,
            ];
        });

        // Return formatted response
        return response()->json([
            "success" => true,
            "data" => $formattedCollections,
            //"pagination" => [
              //  "total" => $collections->total(),
                //"per_page" => $collections->perPage(),
                //"current_page" => $collections->currentPage(),
                //"last_page" => $collections->lastPage(),
                //"from" => $collections->firstItem(),
                //"to" => $collections->lastItem()
            //]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            "success" => false,
            "error" => $e->getMessage()
        ], 500);
    }
}

    




}
