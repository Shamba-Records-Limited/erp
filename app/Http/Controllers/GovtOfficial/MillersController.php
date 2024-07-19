<?php

namespace App\Http\Controllers\GovtOfficial;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MillersController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $millers = DB::select(DB::raw("
            SELECT m.*, u.first_name, u.other_names FROM millers m
            JOIN miller_admin ma ON ma.miller_id = m.id
            JOIN users u ON u.id = ma.user_id
        "));


        return view('pages.govt-official.millers.index', compact('millers'));
    }
}