<?php

namespace App\Http\Controllers\Farmer;

use App\Events\AuditTrailEvent;
use App\Http\Controllers\Controller;
use App\Http\Traits\Vet;
use App\User;
use App\VetBooking;
use App\VetItem;
use App\VetService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VetController extends Controller
{
    use Vet;
    public function __construct()
    {
        return $this->middleware('auth');
    }


    public function index()
    {
        $user = Auth::user();
        $vet_items = VetItem::vet_items($user->cooperative_id);
        $services = VetService::services($user->cooperative_id);
        $bookings = VetBooking::bookings($user);
        return view('pages.as-farmer.vets.bookings', compact('services', 'bookings', 'vet_items'));
    }


    public function get_farmer_bookings()
    {
        $user = Auth::user();
        $bookings = VetBooking::where('farmer_id',$user->id)
            ->orWhere('cooperative_id',$user->cooperative->id)->latest()->limit(100)->get();
        $data = [];
        foreach ($bookings as $b)
        {
            $title = $b->farmer_id === $user->id ? $b->event_name : 'Booked';
            $res = [
                "start" => $b->event_start,
                "end" => $b->event_end,
                "title" => ucwords(strtolower($b->vet->first_name).' '.strtolower($b->vet->other_names)).': '.$title
            ];

            $data[] = $res;
        }

        return response()->json($data);
    }

    public function add_booking(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'vet' => 'required',
            'start' => 'required',
            'duration' => 'required|integer|min:1|max:12',
            'booking_details' => 'required|string',
            'type' => 'required|string',
            'service' => 'required|string',
        ]);
        $user = Auth::user();
        return $this->addBooking($request, $user->id, $user);
    }

    public function edit_bookings(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'edit_vet' => 'required',
            'edit_duration' => 'required|integer|min:1|max:12',
            'edit_booking_details' => 'required|string',
            'edit_type' => 'required|string',
            'edit_service' => 'required|string',
        ]);

        $booking = VetBooking::findOrFail($id);
        $user = Auth::user();
        return $this->editVetBooking($booking, $request, $user, $id, $user->id);
    }

    public function edit_booking_status(Request $request, $bookingId): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'status' => 'required',
            'charges' => 'required|min:0|regex:/^\d+(\.\d{1,2})?$/'
        ]);
        $user = Auth::user();
        $booking = VetBooking::findOrFail($bookingId);
        try {
            DB::beginTransaction();
            return $this->updateStatus($user, $request->charges, $request->status, $booking, $bookingId);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back();
        }
    }

}
