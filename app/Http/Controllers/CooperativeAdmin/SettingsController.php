<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\Cooperative;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class SettingsController extends Controller{
public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(){
        $user = Auth::user();
        $coop_id = $user->cooperative->id;
        $products = Product::all();
        $coop = Cooperative::find($coop_id);

        return view("pages.cooperative-admin.settings.index", compact('coop', "products"));
    }

    public function set_main_product(Request $request){
        $request->validate([
            "main_product_id" => "required|exists:products,id"
        ]);

        try {
            //code...
            $coop_id = Auth::user()->cooperative->id;
            $cooperative = Cooperative::find($coop_id);
            $cooperative->main_product_id = $request->main_product_id;
            $cooperative->save();

            toastr()->success('Main product set Successfully');
            return redirect()->route('cooperative-admin.settings.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }
}