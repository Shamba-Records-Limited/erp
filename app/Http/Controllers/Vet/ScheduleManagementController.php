<?php

namespace App\Http\Controllers\Vet;

use App\Events\AuditTrailEvent;
use App\Http\Controllers\Controller;
use App\User;
use App\VetBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleManagementController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }


    public function index()
    {
        $farmers = User::where('cooperative_id',Auth::user()->cooperative->id)->latest()->get();
        return view('pages.as-vet.bookings', compact('farmers'));
    }

    public function get_vet_bookings(): \Illuminate\Http\JsonResponse
    {
        $bookings = VetBooking::where('vet_id',Auth::user()->id)->latest()->limit(100)->get();
        $data = [];
        foreach ($bookings as $b)
        {
            $res = [
                "start" => $b->event_start,
                "end" => $b->event_end,
                "title" =>  ucwords(strtolower($b->vet->first_name).' '.strtolower($b->vet->other_names)).': '.$b->event_name
            ];

            array_push($data, $res);
        }

        return response()->json($data);
    }

    public function add_booking(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request,[
            'vet' => 'required',
            'start' => 'required',
            'duration' => 'required|integer|min:1|max:12',
            'booking_details' => 'required|string',
        ]);

        $start_time = Carbon::parse( $request->start)->format('Y-m-d H:i:s');
        $end_time = Carbon::parse( $request->start)->addHours($request->duration)->format('Y-m-d H:i:s');

        if(check_if_booked($start_time,$end_time,$request->vet) > 0)
        {
            toastr()->error('Vet is not available during the selected date and time');
            return redirect()->route('farmer.vet.my-bookings.show');
        }else{
            VetBooking::create([
                "vet_id" => $request->vet,
                "farmer_id" => Auth::user()->id,
                "event_start" => $start_time,
                "event_end"=>$end_time,
                "event_name" => $request->booking_details,
                "cooperative_id"=> Auth::user()->cooperative->id
            ]);

            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Creating a booking for vet id '.$request->vet, 'cooperative_id'=> Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));

            toastr()->success('Booking Created Successfully');
            return redirect()->route('farmer.vet.my-bookings.show');
        }

    }

}
