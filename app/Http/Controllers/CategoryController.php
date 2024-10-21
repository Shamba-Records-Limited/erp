<?php

namespace App\Http\Controllers;

use App\Category;
use App\Events\AuditTrailEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function  index()
    {
        $categories = Category::latest()->where('cooperative_id',Auth::user()->cooperative->id)->get();
        return view('pages.cooperative.category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            "name" => "required|string",
        ]);

        try {
            DB::beginTransaction();

            Category::create([
                "name" => $request->name,
                "cooperative_id" => Auth::user()->cooperative->id
            ]);
            DB::commit();
            $data = ['user_id' => Auth::user()->id, 'activity' => 'created  '.$request->name.' Category','cooperative_id'=> Auth::user()->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Category Created Successfully');
            return redirect()->route('cooperative.categories.show');

        }catch (\Throwable $e)
        {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }

    }

    public function edit(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request,[
            "name_edit" => "required|string",
        ]);

        try {
            DB::beginTransaction();
            $category = Category::findOrFail($id);
            $category->name = $request->name_edit;
            $category->save();
            DB::commit();
            $data = ['user_id' => Auth::user()->id, 'activity' => 'Edited  '.$request->name.' Category',
                'cooperative_id'=> Auth::user()->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Category Edited Successfully');
            return redirect()->route('cooperative.categories.show');
        }catch (\Throwable $e)
        {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }
}
