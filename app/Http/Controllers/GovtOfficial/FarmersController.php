<?php

namespace App\Http\Controllers\GovtOfficial;

use App\Http\Controllers\Controller;

class FarmersController extends Controller
{
public function __construct()
    {
        return $this->middleware('auth');
    }


    public function index()
    {
        return view('pages.govt-official.farmers.index');
    }

    public function details($id)
    {
        $farmer = Farmer::find($id);

        $collections = DB::select(DB::raw("
            SELECT usr.username, p.name as product_name, quantity, c.*, pc.unit,
                co.name as cooperative_name, usr.first_name, usr.other_names, f.member_no
            FROM collections c
            JOIN farmers f ON f.id = c.farmer_id
            JOIN users usr ON usr.id = f.user_id
            JOIN products p ON p.id = c.product_id
            JOIN product_categories pc ON pc.id = p.category_id
            JOIN cooperatives co ON co.id = c.cooperative_id
            WHERE f.id = :farmer_id
            ORDER BY c.created_at DESC;
        "), ["farmer_id" => $id]);

        $collectionsTotal = DB::select(DB::raw("
            SELECT Sum(c.quantity) AS total FROM collections c
            WHERE c.farmer_id = :farmer_id
        "), ["farmer_id" => $id])[0]->total;

        return view('pages.govt-official.farmers.detail', compact('farmer', 'collections', 'collectionsTotal'));
    }
}
