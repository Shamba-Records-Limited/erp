<?php

namespace App\Http\Traits;

use App\Events\AuditTrailEvent;
use App\ReportedCase;
use App\User;
use App\VetBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait Disease
{
    public function dashboard($cooperative)
    {
        return DB::select("SELECT COUNT(rc.disease_id) AS count , d.name as disease FROM reported_cases rc
        JOIN diseases d on rc.disease_id = d.id WHERE rc.cooperative_id = '$cooperative' GROUP BY rc.disease_id ORDER BY count DESC LIMIT 4 ");
    }

    public function dashboard_stats($cooperative)
    {
        $case_by_status = DB::select("SELECT COUNT(disease_id) AS count , status FROM reported_cases 
                                           WHERE cooperative_id = '$cooperative' GROUP BY status");
        return [
            "case_by_status" => $case_by_status,
        ];

    }

    public function map_data($cooperative)
    {
        $location_data = DB::select("SELECT l.latitude, l.longitude FROM locations l
                               JOIN farmers f ON l.id = f.location_id JOIN users u ON f.user_id = u.id 
                               INNER JOIN reported_cases rc on u.id = rc.farmer_id
                               WHERE u.cooperative_id = '$cooperative'");
        return [
            "locations" => $location_data
        ];
    }

    /**
     * @param ReportedCase $reported_case
     * @param Request $request
     * @param string $farmerId
     * @param string $cooperativeId
     * @param bool $isUpdate
     * @return void
     */
    public function saveCase(ReportedCase $reported_case, Request $request, string $farmerId, string $cooperativeId, bool $isUpdate = false){
        if($isUpdate){
            $reported_case->farmer_id = $farmerId;
            $reported_case->disease_id = $request->edit_disease;
            $reported_case->status = $request->edit_status;
            $reported_case->symptoms = $request->edit_symptoms;
            $reported_case->cooperative_id = $cooperativeId;
            $reported_case->save();
        }else{
            $reported_case->farmer_id = $farmerId;
            $reported_case->disease_id = $request->disease;
            $reported_case->status = $request->status;
            $reported_case->symptoms = $request->symptoms;
            $reported_case->cooperative_id = $cooperativeId;
            $reported_case->save();
        }

    }

    public function case_book_vet(Request $request, $id, User $user){
        $start_time = Carbon::parse($request->start)->format('Y-m-d H:i:s');
        $end_time = Carbon::parse($request->start)->addHours($request->duration)->format('Y-m-d H:i:s');

        if (check_if_booked($start_time, $end_time, $request->vet) > 0) {
            toastr()->error('Vet is not available during the selected date and time');
            return redirect()->back()->withInput(['vet', 'start', 'duration']);
        } else {

            $reported_case = ReportedCase::findOrFail($id);
            $reported_case->booked = true;
            $booking_details = 'Disease: ' . $reported_case->disease->name;
            VetBooking::create([
                "vet_id" => $request->vet,
                "farmer_id" => $reported_case->farmer->id,
                "event_start" => $start_time,
                "event_end" => $end_time,
                "event_name" => $booking_details,
                "reported_case_id" =>$id,
                "cooperative_id" => $user->cooperative->id
            ]);
            $reported_case->save();

            $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Creating a booking for vet id ' . $request->vet, 'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Vet Booking Created Successfully');
            DB::commit();
            return redirect()->back();

        }
    }
}
