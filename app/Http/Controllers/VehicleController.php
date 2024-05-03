<?php

namespace App\Http\Controllers;

use App\Events\AuditTrailEvent;
use App\Exports\VehiclesExport;
use App\Vehicle;
use App\VehicleType;
use DateTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class VehicleController extends Controller
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
        $vehicles = Vehicle::where('cooperative_id', $coopId)->get();  
        $vehicleTypes = VehicleType::where('cooperative_id', $coopId)->get(['id', 'name']);   
        $drivers = DB::select("select users.id, concat(users.first_name, ' ', users.other_names) as name
            from coop_employees
            join users on users.id = coop_employees.user_id
            join employee_positions on employee_positions.employee_id = coop_employees.id
            join job_positions on job_positions.id = employee_positions.position_id
            where job_positions.position like '%driver%'
            and users.cooperative_id = ?
            order by employee_positions.created_at desc", 
            [$coopId]);

        if ($download != '') {
            return $this->handleDownloads($vehicles, $download);
        }

        return view('pages.cooperative.logistics.vehicles.index', compact('vehicles', 'vehicleTypes', 'drivers'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'registration' => 'required|string',
            'vehicle_type' => 'required|string',
            'driver' => 'required|string',
            'weight' => 'require|numeric',
            'status' => 'required|numeric',
            'status_comment' => 'required|string'
        ]);

        try {

            $user = Auth::user();
            $coopId = $user->cooperative->id;

            Vehicle::create([
                'cooperative_id' => $coopId, 
                'registration_number' => $request->input('registration'), 
                'vehicle_type_id' => $request->input('vehicle_type'),
                'user_id' => $request->input('driver'), 
                'weight' => $request->input('vehicle_weight'), 
                'status' => $request->input('status'),
                'status_date' => (new DateTime)->format('Y-m-d'),
                'status_comment' => $request->input('status_comment'),
            ]);

            event(new AuditTrailEvent([
                'user_id' => $user->id, 
                'activity' => 'created ' . $request->input('registration') . ' Vehicle', 
                'cooperative_id' => $coopId,
            ]));

            toastr()->success('Vehicle created successfully');
            return redirect()->route('cooperative.logistics.vehicles');  
        } catch (Throwable $e) {

            Log::error($e->getMessage());

            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'registration_edit' => 'required|string',
            'vehicle_type_edit' => 'required|string',
            'driver_edit' => 'required|string',
            'weight_edit' => 'require|numeric',
            'status_edit' => 'required|numeric',
            'status_comment_edit' => 'required|string'
        ]);

        try {

            $user = Auth::user();
            $coopId = $user->cooperative->id;

            $vehicle = Vehicle::where('cooperative_id', $coopId)->where('id', $id)->first();
            $vehicle->registration_number = $request->input('registration_edit'); 
            $vehicle->vehicle_type_id = $request->input('vehicle_type_edit');
            $vehicle->user_id = $request->input('driver_edit'); 
            $vehicle->weight = $request->input('vehicle_weight_edit'); 
            $vehicle->status = $request->input('status_edit');
            $vehicle->status_date = (new DateTime)->format('Y-m-d');
            $vehicle->status_comment = $request->input('status_comment_edit');
            $vehicle->save();

            event(new AuditTrailEvent([
                'user_id' => $user->id, 
                'activity' => 'updated ' . $request->input('registration') . ' Vehicle', 
                'cooperative_id' => $coopId,
            ]));

            toastr()->success('Vehicle created successfully');
            return redirect()->route('cooperative.logistics.vehicles'); 
        } catch (Throwable $e) {

            Log::error($e->getMessage());

            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
        return redirect()->route('cooperative.logistics.vehicles');
    }

    private function handleDownloads($data, $type)
    {
        $fileName = 'Vehicles_' . date('Y-m-d') . '.'.$type;

        if ($type != 'pdf') {   
            return Excel::download(new VehiclesExport($data), $fileName);
        }

        if ($type == 'pdf') {
            $data = [
                'title' => 'Vehicles',
                'pdf_view' => 'vehicles',
                'records' => $data,
                'filename' => $fileName,
                'orientation' => 'portrait'
            ];
            return download_pdf($data);
        }
    }
}
