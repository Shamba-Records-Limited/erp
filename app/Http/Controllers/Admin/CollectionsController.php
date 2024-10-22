<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectionsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $title = "Collections";
        // $exportRoute = "collections.export";
        $exportRoute = null;
        $tableRoute = route('admin.collections.partials.table');

        $filters = ['cooperative_name', 'collection_no', 'lot_number', 'farmer', 'product', 'quantity__numeric'];

        return view('components.htmx-table-view', compact('title', 'exportRoute', 'tableRoute', 'filters'));
    }

    public function table(Request $request)
    {
        $tableRoute = route('admin.collections.partials.table');

        // pagination
        $limit = (int)$request->query("limit", "10");
        $page = (int)$request->query("page", "1");
        $offset = ($page - 1) * $limit;

        // search
        $outerCondition = "";
        $search = $request->query("search");
        if($search) {
            $outerCondition = "AND (
                co.name LIKE '%$search%' OR
                collection_number LIKE '%$search%' OR
                lot_number LIKE '%$search%' OR
                usr.username LIKE '%$search%' OR
                p.name LIKE '%$search%' OR
                quantity LIKE '%$search%'
            )";
        }

        // filter
        $rawFilter = $request->query("filter", "");
        $tempFilters = explode(",", $rawFilter);
        $filterMap = ["farmer" => "username"];
        foreach ($filterMap as $term => $interpration) {
            foreach ($tempFilters as $ind=>$filter) {
                if (strpos($filter, $term) !== false) {
                    $tempFilters[$ind] = str_replace($term, $interpration, $filter);
                }
            }
        }
        $rawFilter = implode(",", $tempFilters);
        $outerCondition .= outerConditionFromFilter($rawFilter);

        $rows = DB::select(DB::raw("
            SELECT usr.username, p.name as product_name, quantity, c.*, pc.unit,
                   co.name as cooperative_name, co.abbreviation
            FROM collections c
            JOIN farmers f ON f.id = c.farmer_id
            JOIN users usr ON usr.id = f.user_id
            JOIN products p ON p.id = c.product_id
            JOIN product_categories pc ON pc.id = p.category_id
            JOIN cooperatives co ON co.id = c.cooperative_id
            WHERE TRUE $outerCondition
            ORDER BY c.created_at DESC
            LIMIT :limit OFFSET :offset;
        "), ["limit" => $limit, "offset" => $offset]);

        $collectionsSummation = DB::select(DB::raw("SELECT count(1) AS count, SUM(quantity) AS totalQuantity FROM collections"));
        $totalItems = $collectionsSummation[0]->count;
        $totalValue = $collectionsSummation[0]->totalQuantity;
        $totalsColumn = ["span__5", $totalValue." KG"];

        $lastPage = ceil($totalItems / $limit);

        $columns = [
            ["name" => "Cooperative", "value" => function ($row) {
                return "$row->cooperative_name ($row->abbreviation)";
            }],
            ["name" => "Collection No", "value" => function ($row) {
                return "$row->collection_number";
            }],
            ["name" => "Lot No", "value" => function ($row) {
                return "$row->lot_number";
            }],
            ["name" => "Farmer", "value" => function ($row) {
                $farmersRoute = route('cooperative-admin.farmers.detail', $row->farmer_id);
                return "<a href='$farmersRoute'>$row->username</a>";
            }],
            ["name" => "Product", "value" => function ($row) {
                return "$row->product_name";
            }],
            ["name" => "Quantity", "value" => function ($row) {
                return "$row->quantity $row->unit";
            }], ["name" => "Collection Time", "value" => function ($row) {
                $collection_time_options = config('enums.collection_time');
                return $collection_time_options[$row->collection_time];
            }]
        ];

        return view('components.htmx-table', compact('rows', 'columns', 'page', 'lastPage', 'totalItems', 'tableRoute', 'totalValue', 'totalsColumn'));
    }
}
