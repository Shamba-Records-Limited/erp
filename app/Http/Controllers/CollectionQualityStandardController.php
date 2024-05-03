<?php

namespace App\Http\Controllers;

use App\CollectionQualityStandard;
use App\Events\AuditTrailEvent;
use App\Exports\CollectionExport;
use App\Exports\CollectionQualityStdExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CollectionQualityStandardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $coop = Auth::user()->cooperative->id;
        $standards = CollectionQualityStandard::getStandardQualities($coop);
        return view('pages.cooperative.quality-standards.index', compact('standards'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|string'
        ]);
        try {
            DB::beginTransaction();
            $std = new CollectionQualityStandard();
            $std->name = $request->name;
            $std->cooperative_id =Auth::user()->cooperative->id;
            $std->save();
            DB::commit();
            $data = ['user_id' => Auth::user()->id, 'activity' => 'created  ' . $request->name . ' Quality Standard',
                'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Quality Standard Created Successfully');
            return redirect()->back();

        }catch (\Throwable $ex)
        {
            \Log::error('Request failed::: '.$ex->getMessage());
            DB::rollBack();
            toastr()->error('Oops!! Request Failed');
            return redirect()->back()->withInput(['name']);
        }
    }


    public function edit(Request $request, $id)
    {

        $this->validate($request,[
            'name_edit'=>'required|string'
        ]);
        try {
            DB::beginTransaction();
            $std = CollectionQualityStandard::find($id);
            $std->name = $request->name_edit;
            $std->cooperative_id = Auth::user()->cooperative->id;
            $std->save();
            DB::commit();
            $data = ['user_id' => Auth::user()->id, 'activity' => 'Updated  ' . $std->name . ' Quality Standard',
                'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Quality Standard Updated Successfully');
            return redirect()->back();

        }catch (\Throwable $ex)
        {
            \Log::error('Request failed::: '.$ex->getMessage());
            DB::rollBack();
            toastr()->error('Oops!! Request Failed');
            return redirect()->back();
        }
    }

    public function download($type){

        $standards = CollectionQualityStandard::getStandardQualities(Auth::user()->cooperative->id);
        $file_name = strtolower('standards_' . date('d_m_Y'));
        if ($type != env('PDF_FORMAT')) {
            $file_name .= '.' . $type;
            return Excel::download(new CollectionQualityStdExport($standards), $file_name);
        } else {
            $data = [
                'title' => 'Collection Quality Standards',
                'pdf_view' => 'collection_quality_stds',
                'records' => $standards,
                'filename' => $file_name,
                'orientation' => 'portrait'
            ];
            return download_pdf($data);
        }
    }
}
