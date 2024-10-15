<?php

namespace App\Http\Controllers;

use App\Events\AuditTrailEvent;
use App\Exports\TransportProvidersExport;
use App\TransportProvider;
use App\TransportProviderVehicle;
use App\Vehicle;
use App\VehicleType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class TransportProviderController extends Controller
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
        $transporters = TransportProvider::latest()->where('cooperative_id', $coopId)->get();
        
        if ($download != '') {
            return $this->handleDownloads($transporters, $download);
        }

        return view('pages.cooperative.logistics.transporters.index', compact('transporters'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'phone_number' => 'required|string',
            'location' => 'required|string',
        ]);

        try {

            $user = Auth::user();
            $coopId = $user->cooperative->id;

            TransportProvider::create([
                'cooperative_id' => $coopId, 
                'name' => $request->input('name'), 
                'phone_number' => $request->input('phone_number'), 
                'location' => $request->input('location'),
            ]);

            event(new AuditTrailEvent([
                'user_id' => $user->id, 
                'activity' => 'created ' . $request->input('name') . ' TransportProvider', 
                'cooperative_id' => $coopId,
            ]));

            toastr()->success('Transport provider created successfully');
            return redirect()->route('cooperative.logistics.transporters');  
        } catch (Throwable $e) {

            Log::error($e->getMessage());

            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }

    public function show($id)
    {
        $coopId = Auth::user()->cooperative->id;
        $transporter = TransportProvider::find($id);
        $vehicleTypes = VehicleType::where('cooperative_id', $coopId)->get(['id', 'name']);
        return view('pages.cooperative.logistics.transporters.show', compact('transporter', 'vehicleTypes'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name_edit' => 'required|string',
            'phone_number_edit' => 'required|string',
            'location_edit' => 'required|string',
        ]);

        try {

            $user = Auth::user();
            $coopId = $user->cooperative->id;

            $transporter = TransportProvider::find($id);
            $transporter->update([
                'name' => $request->input('name_edit'), 
                'phone_number' => $request->input('phone_number_edit'), 
                'location' => $request->input('location_edit'),
            ]);

            event(new AuditTrailEvent([
                'user_id' => $user->id, 
                'activity' => 'updated ' . $request->input('name_edit') . ' TransportProvider', 
                'cooperative_id' => $coopId,
            ]));

            toastr()->success('Transport provider updated successfully');
            return redirect()->back();  
        } catch (Throwable $e) {

            Log::error($e->getMessage());

            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }

    public function storeVehicle(Request $request, $id)
    {
        $this->validate($request, [
            'registration' => 'required|string',
            'vehicle_type' => 'required|string',
            'vehicle_weight' => 'required|numeric',
            'driver_name' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ]);
        
        try {

            $user = Auth::user();
            $coopId = $user->cooperative->id;

            $vehicle = TransportProviderVehicle::create([
                'cooperative_id' => $coopId,
                'registration_number' => $request->input('registration'), 
                'vehicle_type_id' => $request->input('vehicle_type'), 
                'transport_provider_id' => $id,
                'weight' => $request->input('vehicle_weight'),
                'driver_name' => $request->input('driver_name'),
                'phone_no' => $request->input('phone_number'),
            ]);

            if (!$vehicle) {
                return redirect()->back()->withInput();
            }

            event(new AuditTrailEvent([
                'user_id' => $user->id, 
                'activity' => 'created ' . $request->input('registration') . ' TransporterVehicle', 
                'cooperative_id' => $coopId,
            ]));

            toastr()->success('Vehicle created successfully');
            return redirect()->back();  
        } catch (Throwable $e) {

            Log::error($e->getMessage());

            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }

    public function updateVehicle(Request $request, $id, $vid)
    {
        $this->validate($request, [
            'registration_edit' => 'required|string',
            'vehicle_type_edit' => 'required|string',
            'weight_edit' => 'required|numeric',
            'driver_name_edit' => 'nullable|string',
            'phone_number_edit' => 'nullable|string',
        ]);

        try {

            $user = Auth::user();
            $coopId = $user->cooperative->id;

            $vehicle = TransportProviderVehicle::find($vid);
            $vehicle->update([
                'registration_number' => $request->input('registration_edit'),
                'vehicle_type_id' => $request->input('vehicle_type_edit'),
                'weight' => $request->input('weight_edit'),
                'driver_name' => $request->input('driver_name_edit'),
                'phone_no' => $request->input('phone_number_edit'),
            ]);

            event(new AuditTrailEvent([
                'user_id' => $user->id, 
                'activity' => 'updated ' . $request->input('registration') . ' TransporterVehicle', 
                'cooperative_id' => $coopId,
            ]));

            toastr()->success('Vehicle updated successfully');
            return redirect()->back();  
        } catch (Throwable $e) {

            Log::error($e->getMessage());

            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }

    public function getVehiclesByTransporterId($id)
    {
        $vehicles = [];
        $coopId = Auth::user()->cooperative->id;
        if ($id == 'own_vehicle') {
            $vehicles = Vehicle::where('cooperative_id', $coopId)
                ->get([ 'id', 'registration_number', 'user_id' ]);
        } else {
            $vehicles = TransportProviderVehicle::where('cooperative_id', $coopId)
                ->where('transport_provider_id', $id)
                ->get([ 'id', 'registration_number', 'driver_name', 'phone_no' ]);
        }

        return response(json_encode($vehicles), 200, [ 'content-type' => 'application/json' ]);
    }

    private function handleDownloads($data, $type)
    {
        $fileName = 'TransportProviders_' . date('Y-m-d') . '.'.$type;

        if ($type != 'pdf') {   
            return Excel::download(new TransportProvidersExport($data), $fileName);
        }

        if ($type == 'pdf') {
            $data = [
                'title' => 'Transport Providers',
                'pdf_view' => 'transport_providers',
                'records' => $data,
                'filename' => $fileName,
                'orientation' => 'portrait'
            ];
            return deprecated_download_pdf($data);
        }
    }
}
