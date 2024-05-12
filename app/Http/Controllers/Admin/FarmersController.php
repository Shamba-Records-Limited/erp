<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;

class FarmersController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $farmers = DB::select(DB::raw("
                SELECT
                    f.id,
                    c.name as country_name,
                    u.username
                FROM farmers f
                JOIN countries c ON f.country_id = c.id
                JOIN users u ON f.user_id = u.id;
            "));

        return view('pages.admin.farmers.index', compact('farmers'));
    }
}
