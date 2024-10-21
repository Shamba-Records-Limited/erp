<?php

namespace App\Http\Controllers\Farmer;

use App\Charts\CollectionsChart;
use App\Collection;
use App\CollectionQualityStandard;
use App\Events\AuditTrailEvent;
use App\Http\Controllers\Controller;
use App\Product;
use App\User;
use Carbon\Carbon;
use EloquentBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $farmer_id = $user->farmer->id;


        $collections = DB::select(DB::raw("
            SELECT p.name as product_name, quantity, c.*, pc.unit,
                f.id as farmer_id, f.member_no, coop.name as coop_name
            FROM collections c
            JOIN farmers f ON f.id = c.farmer_id
            JOIN products p ON p.id = c.product_id
            JOIN product_categories pc ON pc.id = p.category_id
            JOIN cooperatives coop ON coop.id = c.cooperative_id
            WHERE c.farmer_id = :id
            ORDER BY c.created_at DESC;
        "), ["id" => $farmer_id]);


        return view('pages.farmer.collections', compact('collections'));
    }
}
