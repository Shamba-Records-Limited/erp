<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CollectionsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $collections = DB::select(DB::raw("
            SELECT usr.username, p.name as product_name, quantity, c.*, pc.unit,
                   co.name as cooperative_name
            FROM collections c
            JOIN farmers f ON f.id = c.farmer_id
            JOIN users usr ON usr.id = f.user_id
            JOIN products p ON p.id = c.product_id
            JOIN product_categories pc ON pc.id = p.category_id
            JOIN cooperatives co ON co.id = c.cooperative_id
            ORDER BY c.created_at DESC;
        "));


        return view('pages.admin.collections.index', compact('collections'));
    }
}
