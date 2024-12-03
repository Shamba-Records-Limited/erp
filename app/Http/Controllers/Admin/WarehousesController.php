<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class WarehousesController extends Controller
{
       public function __construct()
        {
            return $this->middleware('auth');
        }

        public function index()
        {
              //
        }
}
