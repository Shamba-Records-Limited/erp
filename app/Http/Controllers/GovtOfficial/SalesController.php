<?php

namespace App\Http\Controllers\GovtOfficial;

use App\Http\Controllers\Controller;

class SalesController extends Controller
{
public function __construct()
    {
        return $this->middleware('auth');
    }


    public function index()
    {
        return view('pages.govt-official.sales.index');
    }
}