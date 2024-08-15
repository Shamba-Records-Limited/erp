<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        return view('pages.farmer.dashboard');
    }

}