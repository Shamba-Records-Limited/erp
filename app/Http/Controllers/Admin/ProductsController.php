<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductCategory;
use App\ProductGrade;
use App\Unit;
use App\ProductPricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Log;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function dash()
    {
        return view('pages.admin.products.dash');
    }

    public function list_products()
    {
        $categories = ProductCategory::all();
        $products = DB::select(DB::raw("
            SELECT p.id, p.name, pc.name as category_name, u.name as unit FROM products p
            LEFT JOIN product_categories pc ON p.category_id = pc.id
            LEFT JOIN units u ON p.unit_id = u.id;
        "));
        $units = Unit::all();
        return view('pages.admin.products.products', compact("categories", "products","units"));
    }

    public function store_product(Request $request)
    {
        $request->validate([
            "name" => "required",
            "category_id" => "required|exists:product_categories,id",
        ]);

        try {
            $product = new Product();
            $product->name = $request->name;
            $product->category_id = $request->category_id;
            $product->unit_id = $request->unit_id;
            $product->save();

            toastr()->success('Product Created Successfully');
            return redirect()->route('admin.products.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }

    public function view_edit_product($id)
    {
        $product = Product::find($id);
        $categories = ProductCategory::all();

        return view('pages.admin.products.products-edit', compact('product', 'categories'));
    }

    public function edit_product(Request $request, $id)
    {
        $request->validate([
            "name" => "required|unique:products,name",
            "category_id" => "required|exists:product_categories,id",
        ]);

        try {
            $product = Product::find($id);
            $product->name = $request->name;
            $product->category_id = $request->category_id;
            $product->save();

            toastr()->success('Product Updated Successfully');
            return redirect()->route('admin.products.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }

    public function list_units()
    {
        $units = Unit::all();

        return view('pages.admin.products.units', compact("units"));
    }

    public function store_unit(Request $request)
    {
        $request->validate([
            "name" => "required|unique:units,name",
            "abbreviation" => "required|unique:units,abbreviation"
        ]);

        try {
            $unit = new Unit();
            $unit->name = $request->name;
            $unit->abbreviation = $request->abbreviation;
            $unit->save();

            toastr()->success('Unit Created Successfully');
            return redirect()->route('admin.products.units');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }

    public function view_edit_unit($id)
    {
        $unit = Unit::find($id);

        return view('pages.admin.products.units-edit', compact('unit'));
    }

    public function edit_unit(Request $request, $id)
    {
        $request->validate([
            "name" => "required|unique:units,name,$id",
            "abbreviation" => "required|unique:units,abbreviation,$id"
        ]);

        try {
            $unit = Unit::find($id);
            $unit->name = $request->name;
            $unit->abbreviation = $request->abbreviation;
            $unit->save();

            toastr()->success('Unit updated Successfully');
            return redirect()->route('admin.products.units');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }

    public function delete_unit($id)
    {
        try {
            $unit = Unit::find($id);
            $unit->delete();

            toastr()->success('Unit deleted Successfully');
            return redirect()->route('admin.products.units');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }

    public function list_categories()
    {
        // $categories = ProductCategory::all();
        $categories = DB::select(DB::raw("
            SELECT pc.id, pc.name, pc.unit FROM product_categories pc;
        "));

        return view('pages.admin.products.categories', compact('categories'));
    }

    public function store_category(Request $request)
    {
        $units = [];
        foreach (config('enums.units') as $k => $u) {
            $units[] = $k;
        }

        $request->validate([
            "name" => "required|unique:product_categories,name",
            "unit" => [
                "required",
                Rule::in($units),
            ],
        ]);

        try {
            $category = new ProductCategory();
            $category->name = $request->name;
            $category->unit = $request->unit;
            $category->save();

            toastr()->success('Category Created Successfully');
            return redirect()->route('admin.products.categories');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }

    public function view_edit_category($id)
    {
        $category = ProductCategory::find($id);

        return view('pages.admin.products.categories-edit', compact('category'));
    }

    public function edit_category(Request $request, $id)
    {
        $units = [];
        foreach (config('enums.units') as $k => $u) {
            $units[] = $k;
        }

        $request->validate([
            "name" => "required|unique:product_categories,name,$id",
            "unit" => [
                "required",
                Rule::in($units),
            ],
        ]);

        try {
            $category = ProductCategory::find($id);
            $category->name = $request->name;
            $category->unit = $request->unit;
            $category->save();

            toastr()->success('Category updated Successfully');
            return redirect()->route('admin.products.categories');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }

    public function delete_category($id)
    {
        try {
            $category = ProductCategory::find($id);
            $category->delete();

            toastr()->success('Category deleted Successfully');
            return redirect()->route('admin.products.categories');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }

    public function list_grades()
    {
        $grades = ProductGrade::all();
        return view('pages.admin.products.grades', compact('grades'));
    }

    public function store_grade(Request $request)
    {
        $request->validate([
            "name" => "required|unique:product_grades,name"
        ]);

        try {
            $grade = new ProductGrade();
            $grade->name = $request->name;
            $grade->save();

            toastr()->success('Grade Created Successfully');
            return redirect()->route('admin.products.grades');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }

    public function view_edit_grade($id)
    {
        $grade = ProductGrade::find($id);

        return view('pages.admin.products.grades-edit', compact('grade'));
    }

    public function edit_grade(Request $request, $id)
    {
        $request->validate([
            "name" => "required|unique:product_grades,name,$id"
        ]);

        try {
            $grade = ProductGrade::find($id);
            $grade->name = $request->name;
            $grade->save();

            toastr()->success('Grade updated Successfully');
            return redirect()->route('admin.products.grades');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }

    public function delete_grade($id)
    {
        try {
            $grade = ProductGrade::find($id);
            $grade->delete();

            toastr()->success('Grade deleted Successfully');
            return redirect()->route('admin.products.grades');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }


    public function index()
    {
        $products = DB::select(DB::raw("
            SELECT p.id, p.name, pc.name as category_name FROM products p
            LEFT JOIN product_categories pc ON p.category_id = pc.id;
        "));
        return view('pages.admin.products.index', compact("products"));
    }

    public function detail($id)
    {
        $user = Auth::user();
        $coop_id = $user->id;

        $products = DB::select(DB::raw("
            SELECT p.id, p.name,p.miller_id, pc.name as category_name FROM products p
            LEFT JOIN product_categories pc ON p.category_id = pc.id
            WHERE p.id = :id;
        "),["id"=> $id]);

        $units = Unit::all();

        $product = [];
        if (count($products) > 0) {
            $product = $products[0];
        }

        $pricings = DB::select(DB::raw("
            SELECT pp.*, u.abbreviation as unit_abbr
            FROM product_pricing pp
            JOIN units u ON pp.unit_id = u.id
            WHERE pp.product_id = :product_id 
            ORDER BY min;
        "), ["product_id" => $id]);

        return view('pages.admin.products.detail', compact("product", "units", "pricings"));
    }

    public function store_product_pricing(Request $request)
    {
        $request->validate([
            "product_id"=>"required|exists:products,id",
            "unit_id"=>"required|exists:units,id",
            "min"=>"required",
            "max"=>"",
            "buying_price"=>"required",
            "selling_price"=>"required",
            "buying_vat"=>"required",
            "selling_vat"=>"required",
        ]);
        try {
            $user = Auth::user();
           
            $product = new ProductPricing();
            $product->cooperative_id = $user->cooperative_id;
            $product->product_id = $request->product_id;
            $product->unit_id = $request->unit_id;
            $product->min = $request->min;
            $product->max = $request->max;
            $product->buying_price = $request->buying_price;
            $product->selling_price = $request->selling_price;
            $product->buying_vat = $request->buying_vat;
            $product->selling_vat = $request->selling_vat;
            $product->created_by_id = $user->id;
            $product->save();

            toastr()->success('Product Pricing Created Successfully');
            return redirect()->route('admin.products.detail', $request->product_id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }
}
