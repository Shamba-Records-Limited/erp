<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\ProductGrade;
use App\Unit;
use Illuminate\Http\Request;
use Log;

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

        return view('pages.admin.products.products');
    }

    public function store_product()
    {
        return redirect()->back()->with_input();
    }

    public function list_units()
    {
        $units = Unit::all();

        return view('pages.admin.products.units', compact("units"));
    }

    public function store_unit(Request $request)
    {
        $request->validate([
            "name" => "required|unique:units,name"
        ]);

        try {
            $unit = new Unit();
            $unit->name = $request->name;
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
            "name" => "required|unique:units,name,$id"
        ]);

        try {
            $unit = Unit::find($id);
            $unit->name = $request->name;
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
        $categories = Category::all();
        return view('pages.admin.products.categories', compact('categories'));
    }

    public function store_category(Request $request)
    {
        $request->validate([
            "name" => "required|unique:categories,name"
        ]);

        try {
            $category = new Category();
            $category->name = $request->name;
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
        $category = Category::find($id);

        return view('pages.admin.products.categories-edit', compact('category'));
    }

    public function edit_category(Request $request, $id)
    {
        $request->validate([
            "name" => "required|unique:categories,name,$id"
        ]);

        try {
            $category = Category::find($id);
            $category->name = $request->name;
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
            $category = Category::find($id);
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
}
