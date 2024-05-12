<?php

namespace App\Http\Controllers\GovtOfficial;

use App\Cooperative;
use App\Http\Controllers\Controller;

class CooperativesController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $cooperatives = Cooperative::all();

        return view('pages.govt-official.cooperatives.index', compact('cooperatives'));
    }

    public function details()
    {
        return view('pages.govt-official.cooperatives.detail');
    }

}