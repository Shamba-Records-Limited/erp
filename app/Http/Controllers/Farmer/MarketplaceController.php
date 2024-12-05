<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function __construct()
        {
            $this->middleware('auth');
        }

        public function index() {

            //
        }

        public function dashboard() {

            //
        }

        public function products() {

            //
        }
}
