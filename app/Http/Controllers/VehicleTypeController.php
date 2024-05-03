<?php

namespace App\Http\Controllers;

use App\Events\AuditTrailEvent;
use App\Exports\VehicleTypesExport;
use App\VehicleType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class VehicleTypeController extends Controller
{
    public function __controller()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $download = $request->query('download');

        $types = VehicleType::latest()->where('cooperative_id', Auth::user()->cooperative->id)->get();

        if ($download != '') {
            return $this->handleDownloads($types, $download);
        }

        return view('pages.cooperative.logistics.vehicle_types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        try {

            $user = Auth::user();

            DB::beginTransaction();

                VehicleType::create([
                    'name' => $request->input('name'),
                    'cooperative_id' => $user->cooperative->id,
                ]);

            DB::commit();

            event(new AuditTrailEvent([
                'user_id' => $user->id, 
                'activity' => 'created ' . $request->input('name') . ' VehicleType', 
                'cooperative_id' => $user->cooperative->id,
            ]));

            toastr()->success('Vehicle Type created successfully');
            return redirect()->route('cooperative.logistics.vehicle_types');  
        } catch (Throwable $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name_edit' => 'required|string',
        ]);

        try {

            $user = Auth::user();

            DB::beginTransaction();

                $vehicleType = VehicleType::find($id);
                $vehicleType->name = $request->input('name_edit');
                $vehicleType->save();

            DB::commit();

            event(new AuditTrailEvent([
                'user_id' => $user->id, 
                'activity' => 'updated ' . $request->input('name_edit') . ' VehicleType', 
                'cooperative_id' => $user->cooperative->id,
            ]));

            toastr()->success('Vehicle Type edited successfully');
            return redirect()->route('cooperative.logistics.vehicle_types');  
        } catch (Throwable $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }

    private function handleDownloads($data, $type)
    {
        $fileName = 'Vehicle_types_' . date('Y-m-d') . '.'.$type;

        if ($type != 'pdf') {   
            return Excel::download(new VehicleTypesExport($data), $fileName);
        }

        if ($type == 'pdf') {
            $data = [
                'title' => 'Vehicle Types',
                'pdf_view' => 'vehicle_types',
                'records' => $data,
                'filename' => $fileName,
                'orientation' => 'portrait'
            ];
            return download_pdf($data);
        }
    }
}
