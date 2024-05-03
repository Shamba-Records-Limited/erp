<?php

namespace App\Http\Traits;

use App\Cow;
use App\Crop;
use App\CropCalendarStage;
use App\CropCalendarStageCostBreakdown;
use App\Events\AuditTrailEvent;
use App\FarmerCrop;
use App\FarmerCropProgressTracker;
use App\Product;
use App\Wallet;
use App\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait FarmManagement
{
    private function add_farmer_farm_crop(Request $request, $isFarmer = false): \Illuminate\Http\RedirectResponse
    {
        if ($request->status != 'not started' && $request->start_date == null) {
            toastr()->error('Please select the start date');
            return redirect()->back()->withErrors(['start_date' => 'Start date cannot be empty when project is in progress'])->withInput();
        }

        if ($request->start_date == null) {
            $start_date = Carbon::now();
        } else {
            $start_date = $request->start_date;
        }

        if ($request->type == 1) {
            $request->livestock = null;
        } else {
            $request->crop = null;
        }

        $user = Auth::user();

        if (!$isFarmer && $request->farmer_selection == 'all') {

            if ($request->type == 1) {
                $product_id = Crop::findOrFail($request->crop)->product_id;
                $user_ids = Product::findOrFail($product_id)->farmers()->pluck('farmer_id')->toArray();
                // $user_ids = get_id_of_user_with_role('farmer', $user->cooperative_id);
                $farmers = get_farmers($user_ids, $user->cooperative_id)->pluck('id')->toArray();
            } else {
                $farmers = [Cow::findOrFail($request->livestock)->farmer_id];
            }

        } else {
            $farmers = $isFarmer ? [$user->farmer->id] : $request->farmer;
        }

        try {
            DB::beginTransaction();
            foreach ($farmers as $farmer) {

                $current_stage = CropCalendarStage::find($request->stage);
                $last_date = $this->calculate_last_date($current_stage, $start_date);
                $farmer_crop = new FarmerCrop();
                $farmer_crop->farmer_id = $farmer;
                $farmer_crop->type = $request->type;
                $farmer_crop->crop_id = $request->crop;
                $farmer_crop->livestock_id = $request->livestock;
                $farmer_crop->stage_id = $current_stage->id;
                $farmer_crop->start_date = $start_date;
                $farmer_crop->last_date = $last_date;
                if ($request->next_stage) {
                    $farmer_crop->next_stage_id = $request->next_stage;
                }
                $farmer_crop->total_cost = $this->calculate_cost(json_decode($request->cost));
                $farmer_crop->status = $request->status;
                $farmer_crop->cooperative_id = $user->cooperative_id;
                $farmer_crop->save();
                $this->add_to_progress_tracker($farmer_crop, $last_date, $request, $start_date);
            }

//            if ($this->save_purchase_transaction($request->farmer, $request->cost)) {
            $data = ['user_id' => $user->id, 'activity' => 'Tracing farmer crop for farmer(s): ' . json_encode($request->farmer),
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Added successfully');
//            } else {
//                DB::rollBack();
//                Log::debug('Failed to create a farmer transaction');
//                toastr()->error('Failed to create a farmer transaction');
//            }
            DB::commit();
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Oops! request Failed');
            return redirect()->back();
        }

    }

    private function add_to_progress_tracker($farmer_crop, $last_date, $request, $start_date)
    {
        $costBreakDown = json_decode($request->cost);
        $tracker = new FarmerCropProgressTracker();
        $tracker->farmer_crop_id = $farmer_crop->id;
        $tracker->stage_id = $request->stage;
        $tracker->start_date = $start_date;
        $tracker->last_date = $last_date;
        if ($request->next_stage) {
            $tracker->next_stage_id = $request->next_stage;
        }
        $tracker->cost = $this->calculate_cost($costBreakDown);
        $tracker->status = $request->status;
        $tracker->save();
        if ($costBreakDown && count($costBreakDown) > 0) {
            $this->add_crop_calendar_cost_break_down(json_decode($request->cost), $tracker->refresh()->id);
        }

    }

    private function add_crop_calendar_cost_break_down($costBreakDown, $tracker_id)
    {
        foreach ($costBreakDown as $item) {
            $breakDown = new  CropCalendarStageCostBreakdown();
            $breakDown->item = $item->item;
            $breakDown->amount = $item->amount;
            $breakDown->tracker_id = $tracker_id;
            $breakDown->save();
        }
    }

    private function calculate_cost($costs): int
    {
        $total_cost = 0;
        if ($costs) {
            foreach ($costs as $cost) {
                $total_cost += $cost->amount;
            }
        }
        return $total_cost;
    }

    private function calculate_last_date($stage, $start_date): string
    {
        if ($start_date) {
            $start = Carbon::parse($start_date);
        } else {
            $start = Carbon::now();
        }

        if ($stage == null) {
            return $start->format('Y-m-d');
        }
        $options = config('enums.crop_calendar_period_measure')[0];
        $measure = strtolower($stage->period_measure);
        if ($measure == $options[0]) {
            return $start->addDays($stage->period)->format('Y-m-d');
        }

        if ($measure == $options[1]) {
            return $start->addWeeks($stage->period)->format('Y-m-d');
        }

        if ($measure == $options[2]) {
            return $start->addMonths($stage->period)->format('Y-m-d');
        }

        return $start->addYears($stage->period)->format('Y-m-d');

    }

    private function save_purchase_transaction($farmer_id, $amount): bool
    {
        try {
            DB::beginTransaction();
            $debitAmount = $amount * -1;
            $farmer_wallet = Wallet::where('farmer_id', $farmer_id)->first();
            if ($farmer_wallet) {
                //update current balance
                $farmer_wallet->current_balance -= $amount;
                $farmer_wallet->save();
            } else {
                //create farmer wallet
                $wallet = new Wallet();
                $wallet->farmer_id = $farmer_id;
                $wallet->available_balance = 0;
                $wallet->current_balance = $debitAmount;
                $wallet->save();
            }

            $wallet = Wallet::where('farmer_id', $farmer_id)->first();
            $wallet_transaction = new WalletTransaction();
            $wallet_transaction->wallet_id = $wallet->id;
            $wallet_transaction->type = 'collection';
            $wallet_transaction->amount = $debitAmount;
            $wallet_transaction->reference = 'CROP' . date('Ymdhis');
            $wallet_transaction->source = 'internal';
            $wallet_transaction->initiator_id = Auth::user()->id;
            $wallet_transaction->description = 'Transaction from Crop production stages';
            $wallet_transaction->phone = null;
            $wallet_transaction->save();

            $trx = create_account_transaction('Farmer Payment Statements', $debitAmount, 'Farmer Crop Production');

            if ($trx) {
                DB::commit();
                Log::info(sprintf('Saved a Crop production cost for farmer id: %s', $farmer_id));
                return true;
            } else {
                DB::rollBack();
                Log::debug(sprintf('Failed to Save a Crop product cost for farmer id: %s', $farmer_id));
                return false;
            }

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error(sprintf('Failed to Save a Crop product cost for farmer id: %s, Exception => %s ', $farmer_id, $e->getMessage()));
            return false;
        }
    }

    private function add_farmer_farm_crop_stages(Request $request, $farmer_crop_id): \Illuminate\Http\RedirectResponse
    {
        if ($request->status != 'not started' && $request->start_date == null) {
            toastr()->error('Please select the start date');
            return redirect()->back()->withErrors(['start_date' => 'Start date cannot be empty when project is in progress'])->withInput();
        }

        if ($request->start_date == null) {
            $start_date = Carbon::now();
        } else {
            $start_date = $request->start_date;
        }

        $user = Auth::user();
        try {
            DB::beginTransaction();
            //stage should be of the next stage in previous tracker
            $prev_farmer_crop = FarmerCrop::find($farmer_crop_id);
            if ($request->stage != $prev_farmer_crop->next_stage_id) {
                toastr()->error('You picked the wrong stage');
                return redirect()->back()->withErrors(['stage' => 'You picked the wrong stage, should be next stage of previous tracker'])->withInput();
            }
            $current_stage = CropCalendarStage::find($request->stage);
            $last_date = $this->calculate_last_date($current_stage, $start_date);
            $prev_farmer_crop->stage_id = $request->stage;
            if ($request->next_stage) {
                $prev_farmer_crop->next_stage_id = $request->next_stage;
            }
            $prev_farmer_crop->total_cost += $this->calculate_cost(json_decode($request->cost));
            $prev_farmer_crop->last_date = $last_date;
            $prev_farmer_crop->status = $request->status;
            $prev_farmer_crop->save();
            if (!$this->complete_last_stage($farmer_crop_id)) {
                DB::rollBack();
                Log::debug('Updating stage early');
                toastr()->error('The current stage is not due yet');
                return redirect()->back();
            }
            $this->add_to_progress_tracker($prev_farmer_crop, $last_date, $request, $start_date);
//            if ($this->save_purchase_transaction($prev_farmer_crop->farmer_id, $request->cost)) {
            DB::commit();
            $data = ['user_id' => $user->id, 'activity' => 'Added Tracing Tracker on farmer crop: ' . $prev_farmer_crop->id,
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Added successfully');
//            } else {
//                DB::rollBack();
//                Log::debug('Failed to create a farmer transaction');
//                toastr()->error('Failed to create a farmer  transaction');
//            }
            return redirect()->back();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Oops! request Failed');
            return redirect()->back();
        }
    }

    private function update_main_cost($tracker_id, $additionalCost)
    {
        $tracker = FarmerCropProgressTracker::findOrFail($tracker_id);
        $farmerCrop = FarmerCrop::findOrFail($tracker->farmer_crop_id);
        $tracker->cost += $additionalCost;
        $farmerCrop->total_cost += $additionalCost;
        $tracker->updated_at = Carbon::now();
        $farmerCrop->updated_at = Carbon::now();
        $farmerCrop->save();
        $tracker->save();
    }

    private function cost_break_down_edit(Request $request, $cost_break_down_id): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $costBreakDown = CropCalendarStageCostBreakdown::findOrFail($cost_break_down_id);
            $oldPrice = $costBreakDown->amount;
            $priceChange = ceil($request->amount) - $oldPrice;
            $costBreakDown->amount += $priceChange;
            $costBreakDown->item = $request->item;
            $costBreakDown->tracker->cost += $priceChange;
            $costBreakDown->save();
            $this->update_main_cost($costBreakDown->tracker->id, $priceChange);
            DB::commit();
            toastr()->success('Cost Updated successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error("Error: " . $e->getMessage());
            toastr()->error("Oops an error occurred");
            DB::rollBack();
            return redirect()->back();
        }
    }

    private function new_cost(Request $request, $tracker_id): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $costBreakDown = new CropCalendarStageCostBreakdown();
            $costBreakDown->item = $request->item;
            $costBreakDown->amount = ceil($request->amount);
            $costBreakDown->tracker_id = $tracker_id;
            $this->update_main_cost($tracker_id, ceil($request->amount));
            $costBreakDown->save();
            DB::commit();
            toastr()->success('Cost Updated successfully');
            return redirect()->back();
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error("Error: " . $ex->getMessage());
            toastr()->error('Oops error occurred');
            return redirect()->back();
        }
    }

    private function remove_cost_break_down($cost_break_down_id): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $costBreakDown = CropCalendarStageCostBreakdown::findOrFail($cost_break_down_id);
            $this->update_main_cost($costBreakDown->tracker_id, (-1 * $costBreakDown->amount));
            $costBreakDown->delete();
            toastr()->success('Cost Removed successfully');
            DB::commit();
            return redirect()->back();
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error("Error: " . $ex->getMessage());
            toastr()->error('Oops error occurred');
            return redirect()->back();
        }
    }

    private function complete_last_stage($farmer_crop_id): bool
    {
        $last_crop_stage = FarmerCropProgressTracker::where('farmer_crop_id', $farmer_crop_id)->orderBy('created_at')->first();
        $today = Carbon::now();
        $last_date = Carbon::parse($last_crop_stage->last_date);
        if ($today->lte($last_date)) {
            return false;
        }
        $statuses = config('enums.farmer_crop_status')[0];
        $last_crop_stage->status = $statuses[count($statuses) - 1];
        $last_crop_stage->save();
        return true;
    }

    public function cost_break_down($tracker_id, $page){
        $tracker = FarmerCropProgressTracker::findOrFail($tracker_id);
        $costing = CropCalendarStageCostBreakdown::where('tracker_id', $tracker_id)
            ->orderBy('created_at')
            ->get();
        return view('pages.'.$page, compact('costing', 'tracker'));
    }
}
