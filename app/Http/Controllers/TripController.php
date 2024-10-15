<?php

namespace App\Http\Controllers;

use App\Events\AuditTrailEvent;
use App\Exports\TripsExport;
use App\Location;
use App\Route;
use App\TransportProvider;
use App\TransportProviderVehicle;
use App\Trip;
use App\TripLocation;
use App\Unit;
use App\User;
use App\Vehicle;
use App\WeighBridge;
use App\WeighBridgeEvent;
use Auth;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use Webpatser\Uuid\Uuid;

class TripController extends Controller
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
        $trips = Trip::where('cooperative_id', $coopId)->get();

        $transporters = TransportProvider::where('cooperative_id', $coopId)->get();
        $transporter_vehicles = TransportProviderVehicle::where('cooperative_id', $coopId)->get();
        $vehicles = Vehicle::where('cooperative_id', $coopId)->get();
        $units = Unit::where('cooperative_id', $coopId)->get();
        $weighbridges = WeighBridge::where('cooperative_id', $coopId)->where('status', 1)->get();
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
            return $this->handleDownloads($trips, $download);
        }    
        
        return view('pages.cooperative.logistics.trips.index', compact(
            'trips', 'transporters', 'vehicles', 'transporter_vehicles', 'units', 'weighbridges', 'drivers', 'coopId',
        ));
    }

    public function show($id)
    {
        $coopId = Auth::user()->cooperative->id;
        $trip = Trip::where('cooperative_id', $coopId)->where('id', $id)->first();
        
        return view('pages.cooperative.logistics.trips.show', compact('trip'));   
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'provider' => 'required|string',
            'vehicle' => 'required|string',
            'driver' => 'nullable|string',
            'driver_name' => 'required_unless:provider,own_vehicle',
            'driver_phone' => 'required_unless:provider,own_vehicle',
            'departure_datetime' => 'required|date',
            'departure_location' => 'required|string',
            'departure_weighbridge' => 'required|string',
            'arrival_datetime' => 'required|date',
            'arrival_location' => 'required|string',
            'arrival_weighbridge' => 'required|string',
            'load_type' => 'required|string',
            'load_unit' => 'required|string',
            'trip_cost' => 'required|numeric',
        ]);

        try {

            $user = Auth::user();
            $coopId = $user->cooperative->id;

            $date = (new DateTime)->format('Y-m-d H:i:s');

            $transport_type = $request->input('provider') == 'own_vehicle' ? 'OWN_VEHICLE' : '3RD_PARTY';
            $transport_provider = $transport_type == '3RD_PARTY' ? $request->input('provider') : 'NULL';

            $vehicle = $transport_type == 'OWN_VEHICLE' ? Vehicle::find($request->input('vehicle')) : NULL;

            if (is_null($vehicle)) {
                
                $driver_name = $request->input('driver_name');
                $driver_phone = $request->input('driver_phone');

            } else {

                if (!empty($request->input('driver'))) {

                    $driver = User::find($request->input('driver'));
                    $driver_name = sprintf('%s %s', $driver->first_name, $driver->other_names);
                    $driver_phone = $driver->employee->phone_no ?? '';

                } else {

                    $driver_name = sprintf('%s %s', optional($vehicle->driver)->first_name, optional($vehicle->driver)->other_names);
                    $driver_phone = optional($vehicle->driver->employee)->phone_no;

                }
            }

            $date = (new DateTime())->format('Y-m-d H:i:s');

            DB::beginTransaction();

                DB::statement('SET FOREIGN_KEY_CHECKS = 0');

                $trip_id = (string)Uuid::generate(4);

                // create trip
                DB::insert('INSERT INTO trips (
                            id, cooperative_id, transport_type, transport_provider_id, vehicle_id, driver_name, driver_phone_number,
                            load_type, load_unit, trip_distance, trip_cost_per_km, trip_cost_per_kg, trip_cost_total,
                            status, status_date, status_comment, created_at, updated_at
                        ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )', [
                            $trip_id, $coopId, $transport_type, $transport_provider, $request->input('vehicle'), $driver_name,
                            $driver_phone, $request->input('load_type'), $request->input('load_unit'), 0, 
                            $request->input('trip_cost'), $request->input('trip_cost'), 0, Trip::Scheduled, $date, 'Trip has been scheduled',
                            $date, $date
                        ]);
                
                // add trip locations
                $departure_location = get_location_details($request->input('departure_location'));
                $departure_id = (string)Uuid::generate(4);
                DB::insert('INSERT INTO trip_locations (
                        id, cooperative_id, trip_id, type, location_id, datetime, created_at, updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [
                        $departure_id, $coopId, $trip_id, 'DEPARTURE', $departure_location, $request->input('departure_datetime'),
                        $date, $date
                    ]);

                $arrival_location = get_location_details($request->input('arrival_location'));
                $arrival_id = (string)Uuid::generate(4);
                DB::insert('INSERT INTO trip_locations (
                        id, cooperative_id, trip_id, type, location_id, datetime, created_at, updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [
                        $arrival_id, $coopId, $trip_id, 'ARRIVAL', $arrival_location, $request->input('arrival_datetime'),
                        $date, $date
                    ]);

                // add weighbridge events
                DB::insert('INSERT INTO weigh_bridge_events (
                        id, cooperative_id, weigh_bridge_id, trip_id, trip_location_id, created_at, updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?)', [
                        (string)Uuid::generate(4), $coopId, $request->input('departure_weighbridge'), $trip_id, $departure_id,
                        $date, $date
                    ]);

                DB::insert('INSERT INTO weigh_bridge_events (
                        id, cooperative_id, weigh_bridge_id, trip_id, trip_location_id, created_at, updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?)', [
                        (string)Uuid::generate(4), $coopId, $request->input('arrival_weighbridge'), $trip_id, $arrival_id,
                        $date, $date
                    ]);

                // update trip cost
                $distance = $this->getDistance($departure_location, $arrival_location);
                $trip_cost_total = $distance * (float)$request->input('trip_cost');
                DB::update('UPDATE trips SET trip_distance = ?, trip_cost_total = ? WHERE id = ?', [ $distance, $trip_cost_total, $trip_id ]);

                create_account_transaction('Add Trip Cost', $trip_cost_total, 'Trip booking for: ' . $request->input('departure_datetime'));

                DB::statement('SET FOREIGN_KEY_CHECKS = 1');

            DB::commit();

            event(new AuditTrailEvent([
                'user_id' => $user->id, 
                'activity' => 'created ' . $request->input('name') . ' Trip', 
                'cooperative_id' => $user->cooperative->id,
            ]));

            toastr()->success('Trip created successfully');
            return redirect()->back();
        } catch (Throwable $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }

    public function recordWeight(Request $request)
    {
        $this->validate($request, [
            'event_id' => 'required|string',
            'weight' => 'required|numeric',
        ]);

        try {

            $user = Auth::user();
            $coopId = $user->cooperative->id;

            $event = WeighBridgeEvent::find($request->input('event_id'));
            $event->update([ 'weight' => $request->input('weight') ]);

            event(new AuditTrailEvent([
                'user_id' => $user->id, 
                'activity' => 'recorded ' . $event->trip->type . ' weight for trip '. $event->trip_id, 
                'cooperative_id' => $coopId,
            ]));

            toastr()->success('Weight recorded successfully');
            return redirect()->back();
        } catch (Throwable $e) {

            Log::error($e->getMessage());

            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }

    public function dashboard(Request $request)
    {
        $coopId = Auth::user()->cooperative_id;
        $start = null;
        $end = null;
        if ($request->date) {
            $dates = split_dates($request->date);
            $start = $dates['from'];
            $end = $dates['to'];
        }

        $trips = $this->getTripsTakenCount($start, $end, $coopId);
        $companyVehicles = $this->getCompanyVehiclesCount($coopId);
        $transporterVehicles = $this->getTransporterVehiclesCount($coopId);
        $transporterTrips = $this->getTransporterTripCount($start, $end, $coopId);

        return view('pages.cooperative.logistics.dashboard', [
            'trips_taken' => $trips,
            'company_vehicles' => $companyVehicles,
            'transporter_vehicles' => $transporterVehicles,
            'transporter_trips' => $transporterTrips,
        ]);
    }

    public function locationSearch(Request $request)
    {
        $query = $request->query('query');
        return location_search_maps_api($query);
    }

    public function getDistance($departure_id, $arrival_id)
    {
        $departure = Location::find($departure_id);
        $arrival = Location::find($arrival_id);

        if (is_null($departure) || is_null($arrival)) {
            return 0;
        }
        return calculate_distance_maps_api($departure->place_id, $arrival->place_id);
    }

    private function getTripsTakenCount($start, $end, $coopId)
    {
        $bindings = [];
        $query = "SELECT COUNT(id) AS count FROM trips WHERE cooperative_id = ? ";
        $bindings = [$coopId];

        if (!is_null($start) && !is_null($end)) {
            $query .= " AND created_at BETWEEN ? AND ?";
            $bindings = array_merge($bindings, [$start, $end]);
        }

        $trips = DB::select($query, $bindings);

        return count($trips) ? $trips[0]->count : 0;
    }

    private function getCompanyVehiclesCount($coopId)
    {
        $vehicles = DB::select("SELECT 
                COUNT(id) AS count 
            FROM 
                vehicles 
                WHERE cooperative_id = ? ",
            [ $coopId ]);

        return count($vehicles) ? $vehicles[0]->count : 0;
    }

    private function getTransporterVehiclesCount($coopId)
    {
        $vehicles = DB::select("SELECT
                COUNT(id) AS count
            FROM 
                transport_provider_vehicles
                WHERE cooperative_id = ? ",
            [ $coopId ]);

        return count($vehicles) ? $vehicles[0]->count : 0;
    }

    private function getTransporterTripCount($start, $end, $coopId)
    {
        $bindings = [];
        $query = "SELECT
                COUNT(trips.id) AS count, transport_providers.name AS transporter
            FROM
                trips
                JOIN transport_providers ON transport_providers.id = trips.transport_provider_id
            WHERE 
                trips.cooperative_id = ? ";
        $bindings = [$coopId];

        if (!is_null($start) && !is_null($start)) {
            $query .= " AND trips.created_at BETWEEN ? AND ? ";
            $bindings = array_merge($bindings, [$start, $end]);
        }

        $query .= " GROUP BY transport_providers.id";

        $trips = DB::select($query, $bindings);

        return $trips;
    }

    private function handleDownloads($data, $type)
    {
        $fileName = 'Trips_' . date('Y-m-d') . '.'.$type;

        if ($type != 'pdf') {   
            return Excel::download(new TripsExport($data), $fileName);
        }

        if ($type == 'pdf') {
            $data = [
                'title' => 'Trips',
                'pdf_view' => 'trips',
                'records' => $data,
                'filename' => $fileName,
                'orientation' => 'landscape'
            ];
            return deprecated_download_pdf($data);
        }
    }
}
