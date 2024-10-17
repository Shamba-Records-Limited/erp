<?php

namespace App\Http\Traits;

use App\Events\AuditTrailEvent;
use App\User;
use App\VetBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;

trait Vet
{

    public function addBooking(Request $request, $farmerId, User $user): \Illuminate\Http\RedirectResponse
    {
        $start_time = Carbon::parse($request->start)->format('Y-m-d H:i:s');
        $end_time = Carbon::parse($request->start)->addHours($request->duration)->format('Y-m-d H:i:s');

        if (check_if_booked($start_time, $end_time, $request->vet) > 0) {
            toastr()->error('Vet is not available during the selected date and time');
            return redirect()->back()->withInput();
        } else {
            VetBooking::create([
                "vet_id" => $request->vet,
                "farmer_id" => $farmerId,
                "event_start" => $start_time,
                "event_end" => $end_time,
                "event_name" => $request->booking_details,
                "booking_type" => $request->type,
                "service_id" => $request->service,
                "cooperative_id" => $user->cooperative->id
            ]);

            $audit_trail_data = ['user_id' => $user->id,
                'activity' => 'Creating a booking for vet id ' . $request->vet,
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));

            toastr()->success('Vet Booking Created Successfully');
            return redirect()->back();
        }
    }

    /**
     * @param VetBooking $booking
     * @param Request $request
     * @param User $user
     * @param string $id
     * @param $farmerId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editVetBooking(VetBooking $booking, Request $request, User $user, string $id, $farmerId): \Illuminate\Http\RedirectResponse
    {
        if ($request->edit_start) {
            $start_time = Carbon::parse($request->edit_start)->format('Y-m-d H:i:s');
            $end_time = Carbon::parse($request->edit_start)->addHours($request->edit_duration)->format('Y-m-d H:i:s');
        } else {
            $start_time = Carbon::parse($booking->event_start)->format('Y-m-d H:i:s');
            $end_time = Carbon::parse($booking->event_start)->addHours($request->edit_duration)->format('Y-m-d H:i:s');
        }

        if (check_if_booked($start_time, $end_time, $request->edit_vet, $id) > 0) {
            toastr()->error('Vet is not available during the selected date and time');
            return redirect()->route('cooperative.vet.bookings.show');
        } else {
            $booking->vet_id = $request->edit_vet;
            $booking->farmer_id = $farmerId;
            $booking->event_start = $start_time;
            $booking->event_end = $end_time;
            $booking->event_name = $request->edit_booking_details;
            $booking->booking_type = $request->edit_type;
            $booking->service_id = $request->edit_service;
            $booking->save();
            $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Updated a booking for vet id ' . $request->edit_vet, 'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Vet Booking Updated Successfully');
            return redirect()->back();
        }
    }

    /**
     * @param User $user
     * @param float $charges
     * @param $status
     * @param VetBooking $booking
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function updateStatus(User $user, float $charges, $status, VetBooking $booking, string $bookingId): \Illuminate\Http\RedirectResponse
    {
        $amount = $charges;
        if ($amount > 0) {
            Log::debug("Farmer needs to be charged");
            //check if the farmer has a wallet;
            $wallet = $booking->farmer->farmer->wallet;
            $warningMessage = 'Farmer does not have a enough funds to cater for the booking';
            if($wallet == null){
                Log::debug("Farmer does not have a wallet!");
                toastr()->error($warningMessage);
                return redirect()->back();
            }
            if($wallet->current_balance >= $amount){
                Log::debug("Deduct from current balance!");
                $wallet->current_balance -= $amount;
            }elseif ($wallet->available_balance >= $amount){
                Log::debug("Deduct from available balance!");
                $wallet->available_balance -= $amount;
            }else{
                Log::debug("Not enough balance in the wallet for {$amount}, Current balance {$wallet->current_balance}, Available balance {$wallet->available_balance}");
                toastr()->error($warningMessage);
                return redirect()->back();
            }

            $wallet->save();

            record_wallet_transaction(
                $amount,
                $wallet->id,
                "Vet Service",
                "VET_SERVICES",
                'Vet & extension service expense',
                $user->id
            );
            create_account_transaction('Vet Charges', $amount, 'Sold vet items');
            Log::info("Wallet Updated");
        }


        $booking->status = $status;
        $booking->charges += $charges;
        $booking->save();
        DB::commit();
        $audit_trail_data = ['user_id' => $user->id,
            'activity' => 'Updated a booking status for booking id ' . $bookingId,
            'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Vet Booking status updated Successfully');

        return redirect()->back();
    }
}
