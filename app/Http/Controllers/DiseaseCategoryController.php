<?php

namespace App\Http\Controllers;

use App\DiseaseCategory;
use App\Events\AuditTrailEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DiseaseCategoryController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $categories = DiseaseCategory::categories(Auth::user()->cooperative->id);
        return view('pages.cooperative.disease.category', compact('categories'));
    }

    public function store(Request  $request)
    {
        $this->validate($request,[
            'name' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $disease_category = new DiseaseCategory();
            $this->persist($request, $disease_category);
            DB::commit();
            $data = ['user_id' => Auth::user()->id, 'activity' => 'created  ' . $request->name . ' Disease Category', 'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Category Created Successfully');
            return redirect()->route('cooperative.disease.categories');
        }catch (\Throwable $exception)
        {
            DB::rollBack();
            Log::error($exception->getMessage());
            toastr()->error('Oops! Request could not be processed at the moment');
            return redirect()->back();
        }
    }

    private function persist($request, $disease_category)
    {
        try {
            DB::beginTransaction();
            $disease_category->name = $request->name;
            $disease_category->cooperative_id = Auth::user()->cooperative->id;
            $disease_category->save();
            DB::commit();
        }catch (\Throwable $exception)
        {
            DB::rollBack();
            Log::error($exception->getMessage());
        }

    }

    public function edit(Request $request, $id)
    {
        $this->validate($request,[
            'category_name' => 'required|string'
        ]);

        try {
            $disease_category = DiseaseCategory::find($id);
            $disease_category->name = $request->category_name;
            $disease_category->save();
            DB::commit();
            $data = ['user_id' => Auth::user()->id, 'activity' => 'Edited  ' . $id . ' Disease Category Id', 'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Category Updated Successfully');
            return redirect()->route('cooperative.disease.categories');

        }catch (\Throwable $exception)
        {
            DB::rollBack();
            Log::error($exception->getMessage());
            toastr()->error('Oops! Request could not be processed at the moment');
            return redirect()->back();
        }
    }
}
