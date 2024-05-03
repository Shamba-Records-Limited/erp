<?php

namespace App\Http\Controllers;

use App\Disease;
use App\DiseaseCategory;
use App\Events\AuditTrailEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DiseaseController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $disease_categories = DiseaseCategory::categories($user->cooperative_id);
        $diseases = Disease::diseases($user->cooperative->id);
        return view('pages.cooperative.disease.disease', compact('disease_categories', 'diseases'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'category_id' => 'required|string'
        ]);

        try {
            DB::beginTransaction();
            $disease = new Disease();
            $this->persist($request, $disease);
            DB::commit();
            $data = ['user_id' => Auth::user()->id, 'activity' => 'created  ' . $request->name . ' Disease ', 'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Disease Created Successfully');
            return redirect()->route('cooperative.disease.show');
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            toastr()->error('Oops! Request could not be processed at the moment');
            return redirect()->back();
        }
    }

    private function persist($request, $disease)
    {
        try {
            DB::beginTransaction();
            $disease->name = $request->name;
            $disease->disease_category_id = $request->category_id;
            $disease->cooperative_id = Auth::user()->cooperative->id;
            $disease->save();
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
        }

    }

    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'disease_name' => 'required|string',
            'disease_category_id' => 'required|string'
        ]);

        try {
            $disease = Disease::find($id);
            $disease->name = $request->disease_name;
            $disease->disease_category_id = $request->disease_category_id;
            $disease->save();
            DB::commit();
            $data = ['user_id' => Auth::user()->id, 'activity' => 'Updated  ' . $disease->id . ' Disease ',
                'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Disease Updated Successfully');
            return redirect()->route('cooperative.disease.show');
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            toastr()->error('Oops! Request could not be processed at the moment');
            return redirect()->back();
        }

    }
}
