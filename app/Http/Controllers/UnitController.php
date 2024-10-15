<?php

namespace App\Http\Controllers;

use App\Events\AuditTrailEvent;
use App\Exports\UnitExport;
use App\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $units = Unit::where('cooperative_id', Auth::user()->cooperative->id)->latest()->get();
        return view('pages.cooperative.unit.index', compact('units'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => "required|string",
        ]);

        $user = Auth::user();

        try {
            DB::beginTransaction();

            $duplicate = Unit::where('name', $request->name)
                ->where('cooperative_id', $user->cooperative_id)
                ->count() > 0;

            if($duplicate){
                toastr()->error("{$request->name} is already registered");
                return redirect()->back();
            }
            Unit::create([
                "name"=>$request->name,
                "cooperative_id"=> $user->cooperative->id
            ]);
            DB::commit();

            $data = ['user_id' => $user->id, 'activity' => 'created  '.$request->name.' Unit','cooperative_id'=> Auth::user()->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Unit Created Successfully');
            return redirect()->route('cooperative.units.show');

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }


    }

    public function edit(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            "name_edit" => "required|string",
        ]);

        try {
            $user = Auth::user();
            DB::beginTransaction();
            $unit = Unit::findOrFail($id);
            $unit->name = $request->name_edit;
            $unit->save();
            DB::commit();

            $data = ['user_id' => $user->id, 'activity' => 'updated  ' . $unit->id . ' unit',
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Unit Edited Successfully');
            return redirect()->route('cooperative.units.show');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }


    public function export_units($type)
    {
        $cooperative = Auth::user()->cooperative;
        $file_name = strtolower($cooperative->name.'_units');
        if ($type != env('PDF_FORMAT')) {
            $file_name .='.'.$type;
            return Excel::download(new UnitExport($cooperative->id), $file_name);
        } else {
            $data = [
                'title' => $cooperative->name.' Units',
                'pdf_view' => 'units',
                'records' => Unit::units($cooperative->id),
                'filename' => $file_name,
                'orientation' => 'portrait'
            ];
            return deprecated_download_pdf($data);
        }

    }


}
