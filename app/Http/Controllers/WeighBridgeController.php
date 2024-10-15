<?php

namespace App\Http\Controllers;

use App\Events\AuditTrailEvent;
use App\Exports\WeighbridgesExport;
use App\WeighBridge;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class WeighBridgeController extends Controller
{
    //
    public function __controller()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $download = $request->query('download');

        $coopId = Auth::user()->cooperative->id;
        $weighbridges = WeighBridge::where('cooperative_id', $coopId)->get(); 

        $nextId = count($weighbridges) > 0 ? (int)explode('/', $weighbridges->last()->code)[1] + 1 : 1;
        $nextId = sprintf('WB/%s', str_pad($nextId,3,'0',STR_PAD_LEFT));

        if ($download != '') {
            return $this->handleDownloads($weighbridges, $download);
        }

        return view('pages.cooperative.logistics.weighbridges.index', compact('weighbridges', 'coopId', 'nextId'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|string',
            'location' => 'required|string',
            'max_weight' => 'required|numeric',
            'status' => 'required|numeric',
            'status_comment' => 'required|string',
        ]);

        try {

            $user = Auth::user();
            $coopId = $user->cooperative->id;

            $location = get_location_details($request->input('location')); \Log::info('location', [$location]);
            WeighBridge::create([
                'cooperative_id' => $coopId, 
                'code' => $request->input('code'), 
                'location_id' => $location, 
                'max_weight' => $request->input('max_weight'), 
                'status' => $request->input('status'), 
                'status_date' => (new DateTime)->format('Y-m-d'), 
                'status_comment' => $request->input('status_comment'),
            ]);

            event(new AuditTrailEvent([
                'user_id' => $user->id, 
                'activity' => 'created ' . $request->input('code') . ' WeighBridge', 
                'cooperative_id' => $coopId,
            ]));

            toastr()->success('WeighBridge created successfully');
            return redirect()->route('cooperative.logistics.weighbridges');  
        } catch (Throwable $e) {

            Log::error($e->getMessage());

            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    { 
        $this->validate($request, [
            'code_edit' => 'required|string',
            'location_edit' => 'required|string',
            'max_weight_edit' => 'required|numeric',
            'status_edit' => 'required|numeric',
            'status_comment_edit' => 'required|string',
        ]);

        try {

            $user = Auth::user();
            $coopId = $user->cooperative->id;

            $weighbridge = WeighBridge::find($id);
            $location = get_location_details($request->input('location_edit'));
            $weighbridge->update([
                'code' => $request->input('code_edit'), 
                'location_id' => $location, 
                'max_weight' => $request->input('max_weight_edit'), 
                'status' => $request->input('status_edit'), 
                'status_date' => $weighbridge->status != $request->input('status_edit') ? (new DateTime)->format('Y-m-d') : $weighbridge->status_date, 
                'status_comment' => $request->input('status_comment_edit'),
            ]);

            event(new AuditTrailEvent([
                'user_id' => $user->id, 
                'activity' => 'updated ' . $request->input('code_edit') . ' WeighBridge', 
                'cooperative_id' => $coopId,
            ]));

            toastr()->success('WeighBridge updated successfully');
            return redirect()->route('cooperative.logistics.weighbridges');  
        } catch (Throwable $e) {

            Log::error($e->getMessage());

            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }

    public function show($id)
    {
        $coopId = Auth::user()->cooperative->id;
        $weighbridge = WeighBridge::where('cooperative_id', $coopId)->where('id', $id)->first();
        return view('pages.cooperative.logistics.weighbridges.show', compact('weighbridge'));
    }

    private function handleDownloads($data, $type)
    {
        $fileName = 'Weighbridges_' . date('Y-m-d') . '.'.$type;

        if ($type != 'pdf') {   
            return Excel::download(new WeighbridgesExport($data), $fileName);
        }

        if ($type == 'pdf') {
            $data = [
                'title' => 'Weighbridges',
                'pdf_view' => 'weighbridges',
                'records' => $data,
                'filename' => $fileName,
                'orientation' => 'portrait'
            ];
            return deprecated_download_pdf($data);
        }
    }
}
