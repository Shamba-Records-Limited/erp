<?php

namespace App\Http\Controllers;

use App\Breed;
use App\Cow;
use App\Crop;
use App\CropCalendarStage;
use App\CropCalendarStageCostBreakdown;
use App\Events\AuditTrailEvent;
use App\Exports\FarmCropExport;
use App\Exports\FarmerCalendarExport;
use App\Farmer;
use App\FarmerCrop;
use App\FarmerCropProgressTracker;
use App\FarmerExpectedYield;
use App\FarmerYield;
use App\FarmUnit;
use App\Http\Traits\CropManagement;
use App\Http\Traits\Farm;
use App\Http\Traits\FarmManagement;
use App\Product;
use App\Unit;
use App\Wallet;
use App\WalletTransaction;
use Carbon\Carbon;
use Excel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CropManagementController extends Controller
{
    use Farm, FarmManagement, CropManagement;

    public function __contrust()
    {
        $this->middleware('auth');
    }

    public function calendar_stages()
    {
        $user = Auth::user();
        $cropCalendarStages = CropCalendarStage::stages($user->cooperative_id, CropCalendarStage::TYPE_CROP);
        $livestockCalendarStages = CropCalendarStage::stages($user->cooperative_id, CropCalendarStage::TYPE_LIVESTOCK);


        $crops = Crop::select('id', 'product_id', 'variety')->where('cooperative_id', $user->cooperative_id)->with('product')->get();
        $livestock = Cow::select('cows.id', 'cows.name', 'cows.animal_type', 'breeds.name as breed')
            ->join('breeds', 'breeds.id', '=', 'cows.breed_id')
            ->where('cows.cooperative_id', $user->cooperative_id)->get();
        return view('pages.cooperative.farm.crop-calendar-stages', compact('cropCalendarStages', 'crops', 'livestock', 'livestockCalendarStages'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws Exception
     */
    public function add_calendar_stages(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'stages' => 'required',
            'crop' => 'required_if:type,==,1',
            'livestock' => 'required_if:type,==,2',
            'type' => 'required'
        ]);

        try {
            if ($request->type == 1) {
                $cropStages = CropCalendarStage::where('crop_id', $request->crop)->count();
                if($cropStages > 0){
                    toastr()->error("The crop has some set stages, kindly update on the existing one");
                    return redirect()->back();
                }

                $request->livestock = null;
            } else {
                $livestockStages = CropCalendarStage::where('livestock_id', $request->livestock)->count();
                if($livestockStages > 0){
                    toastr()->error("The livestock has some set stages, kindly update on the existing one");
                    return redirect()->back();
                }
                $request->crop = null;
            }
            $user = Auth::user();
            $stageData = (object)[
                "stages" => $request->stages,
                "type" => $request->type,
                "crop" => $request->crop,
                "livestock" => $request->livestock
            ];

            $this->addCalendarStages($stageData, $user);

            $data = [
                'user_id' => $user->id, 'activity' => 'created new crop calendar stage: ' . $request->name,
                'cooperative_id' => $user->cooperative->id
            ];
            event(new AuditTrailEvent($data));
            toastr()->success('Calendar Stage Created Successfully');
            return redirect()->route('cooperative.farm.crop-calendar-stages');
        }catch (Exception $e){
            Log::error("Exception: ". $e->getMessage());
            toastr()->error('Oops request failed!');
            return redirect()->back();
        } catch (\Throwable $e) {
            Log::error("Exception: ". $e->getMessage());
            toastr()->error('Oops request failed!');
            return redirect()->back();
        }

    }

    public function calendar_stage_stages($type, $id){
        if($type == CropCalendarStage::TYPE_CROP){
            $cropCalendarStages = CropCalendarStage::stagesByItem(CropCalendarStage::TYPE_CROP, $id);
            if(count($cropCalendarStages) == 0){
                return redirect()->route('cooperative.farm.crop-calendar-stages');
            }
            return view('pages.cooperative.farm.crop-calendar-stages-stages', compact('cropCalendarStages'));
        }else{
            $livestockCalendarStages = CropCalendarStage::stagesByItem(CropCalendarStage::TYPE_LIVESTOCK, $id);
            if(count($livestockCalendarStages) == 0){
                return redirect()->route('cooperative.farm.crop-calendar-stages');
            }
            return view('pages.cooperative.farm.livestock-calendar-stages-stages', compact(   'livestockCalendarStages'));
        }
    }

    /**
     * @param Request $request
     * @param $type
     * @param $cropId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function add_calendar_stage_stages(Request $request, $type, $Id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'stages' => 'required'
        ]);
        try {
            $user = Auth::user();
            $stageData = (object)[
                "stages" => $request->stages,
                "type" => $type,
                "crop" => $type == CropCalendarStage::TYPE_CROP ? $Id : null,
                "livestock" => $type == CropCalendarStage::TYPE_LIVESTOCK ? $Id : null
            ];

            $this->addCalendarStages($stageData, $user);

            $data = [
                'user_id' => $user->id, 'activity' => 'added new stages to  new crop calendar stage product: ' . $Id,
                'cooperative_id' => $user->cooperative->id
            ];
            event(new AuditTrailEvent($data));
            toastr()->success('Calendar Stage Updated Successfully');
            return redirect()->back();
        }catch (Exception $e){
            Log::error("Exception: ". $e->getMessage());
            toastr()->error('Oops request failed!');
            return redirect()->back();
        } catch (\Throwable $e) {
            Log::error("Exception: ". $e->getMessage());
            toastr()->error('Oops request failed!');
            return redirect()->back();
        }
    }

    public function edit_calendar_stage_stages(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
           'stage' => 'required',
           'period' => 'required|regex:/^\d+(\.\d{0})?$/'
        ]);

        $calendarStage = CropCalendarStage::findOrFail($id);
        $calendarStage->name = $request->stage;
        $calendarStage->period = $request->period;
        $calendarStage->updated_at = Carbon::now();
        $calendarStage->save();
        $user = Auth::user();
        $data = [
            'user_id' => $user->id, 'activity' => 'Updated calendar stage: ' . $id,
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($data));
        toastr()->success('Calendar Stage Updated Successfully');
        return redirect()->back();

    }

    /**
     * @throws Exception
     */
    public function delete_calendar_stage_stages($id): \Illuminate\Http\RedirectResponse
    {
        try {

            $calendarStage = CropCalendarStage::findOrFail($id);
            $calendarStage->delete();
            $user = Auth::user();
            $data = [
                'user_id' => $user->id, 'activity' => 'Deleted calendar stage: ' . $id,
                'cooperative_id' => $user->cooperative->id
            ];
            event(new AuditTrailEvent($data));
            toastr()->success('Calendar Stage Removed');
            return redirect()->back();

        }catch (Exception $e){
            toastr()->error('You can not delete all the stages. Proceed with editing');
            return redirect()->back();
        }

    }

    public function crop()
    {
        $user = Auth::user();
        $crops = Crop::crops($user->cooperative_id);
        $farm_units = FarmUnit::farmUnits($user->cooperative_id);
        $products = Product::select('id', 'name', 'category_id')->where('cooperative_id', $user->cooperative_id)->with('category')->get();
        return view('pages.cooperative.farm.crop', compact('crops', 'farm_units', 'products'));
    }

    public function addCrop(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        $this->validate($request, [
            'product' => 'required',
            'variety' => 'required',
            'farm_unit' => 'required',
            'recommended_areas' => 'required',
            'description' => 'sometimes|nullable|string'
        ]);

        $crop = new Crop();
        $crop->product_id = $request->product;
        $crop->variety = $request->variety;
        $crop->farm_unit_id = $request->farm_unit;
        $crop->recommended_areas = $request->recommended_areas;
        if ($request->description) {
            $crop->description = $request->description;
        }
        $crop->cooperative_id = $user->cooperative_id;
        $crop->save();
        $data = [
            'user_id' => $user->id, 'activity' => 'Added new crop: ' . $request->name,
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($data));
        toastr()->success('Crop added Successfully');
        return redirect()->route('cooperative.farm.crops');
    }

    public function editCrop(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        $this->validate($request, [
            'edit_product' => 'required',
            'edit_variety' => 'required',
            'edit_recommended_areas' => 'required',
            'edit_farm_unit' => 'required',
            'edit_description' => 'sometimes|nullable|string'
        ]);

        $crop = Crop::find($id);
        $crop->product_id = $request->edit_product;
        $crop->variety = $request->edit_variety;
        $crop->farm_unit_id = $request->edit_farm_unit;
        $crop->recommended_areas = $request->edit_recommended_areas;
        if ($request->edit_description) {
            $crop->description = $request->edit_description;
        }
        $crop->save();
        $data = [
            'user_id' => $user->id, 'activity' => 'Updated new crop: ' . $request->edit_name,
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($data));
        toastr()->success('Crop Updated Successfully');
        return redirect()->route('cooperative.farm.crops');
    }

    public function farmer_crops()
    {
        $user = Auth::user();
        $cooperative = $user->cooperative_id;
        $farmers = get_cooperative_farmers($cooperative);
        $crops = Crop::where('cooperative_id', $cooperative)->get();
        $stages = CropCalendarStage::where('cooperative_id', $cooperative)->get();
        $farmer_crops = FarmerCrop::farmerCrops($user, 0);
        $livestock = Cow::select('cows.id', 'cows.name', 'cows.animal_type', 'breeds.name as breed')
            ->join('breeds', 'breeds.id', '=', 'cows.breed_id')
            ->where('cows.cooperative_id', $cooperative)->get();
        return view('pages.cooperative.farm.farmer-crop', compact('farmer_crops', 'farmers', 'crops', 'stages', 'livestock'));
    }

    public function add_farmer_crop(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'farmer_selection' => 'required',
            'farmer' => 'required_if:farmer_selection,==,some',
            'type' => 'required',
            'crop' => 'required_if:type,==,1',
            'livestock' => 'required_if:type,==,2',
            'stage' => 'required|different:next_stage',
            'next_stage' => 'sometimes|nullable|different:stage',
            'cost' => 'sometimes|nullable',
            'status' => 'required',
            'start_date' => 'sometimes|nullable|date',
        ]);

        return $this->add_farmer_farm_crop($request);
    }


    public function add_farmer_crop_stages(Request $request, $farmer_crop_id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'stage' => 'required|different:next_stage',
            'next_stage' => 'sometimes|nullable|different:stage',
            'cost' => 'required',
            'status' => 'required',
            'start_date' => 'sometimes|nullable|date',
        ]);

        return $this->add_farmer_farm_crop_stages($request, $farmer_crop_id);
    }

    public function farmer_crop_stages($farmer_crop_id, $type)
    {
        if ($type == 1) {
            $stages = FarmerCrop::findOrFail($farmer_crop_id)->crop->stages;
        } else {
            $stages = FarmerCrop::findOrFail($farmer_crop_id)->livestock->stages;
        }

        $trackers = FarmerCropProgressTracker::where('farmer_crop_id', $farmer_crop_id)->orderBy('created_at')->get();
        if ($stages == null || $trackers == null) {
            return redirect()->back();
        }
        return view('pages.cooperative.farm.farmer-crop-trackers', compact('trackers', 'farmer_crop_id', 'stages'));
    }

    public function get_cost_break_down($tracker_id)
    {
        return $this->cost_break_down($tracker_id,'cooperative.farm.cost-break-downs');
    }

    public function edit_cost_break_down($cost_break_down_id, Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'item' => 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);
        return $this->cost_break_down_edit($request, $cost_break_down_id);
    }

    public function add_new_cost(Request $request, $tracker_id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'item' => 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);
        return $this->new_cost($request, $tracker_id);
    }

    public function delete_cost_break_down($cost_break_down_id): \Illuminate\Http\RedirectResponse
    {
       return $this->remove_cost_break_down($cost_break_down_id);
    }

    public function edit_progress_tracker(Request $request, $tracker_id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'edit_stage' => 'required|different:next_stage',
            'edit_next_stage' => 'sometimes|nullable|different:stage',
            'edit_status' => 'required',
            'edit_start_date' => 'sometimes|nullable|date',
        ]);

        $user = Auth::user();
        try {
            DB::beginTransaction();
            $tracker = FarmerCropProgressTracker::find($tracker_id);
            $farmer_crop = FarmerCrop::find($tracker->farmer_crop_id);

            //check if stage is same
            if ($request->edit_stage != $tracker->stage_id) {
                $tracker->stage_id = $request->edit_stage;
                $farmer_crop->stage_id = $request->edit_stage;
                $current_stage = CropCalendarStage::find($request->edit_stage);
                $last_date = $this->calculate_last_date($current_stage, $request->edit_start_date);
                $farmer_crop->last_date = $last_date;
                $tracker->last_date = $last_date;
            }

            //check if next stage is same
            if ($request->edit_next_stage != $tracker->next_stage_id) {
                $tracker->next_stage_id = $request->edit_next_stage;
                $farmer_crop->next_stage_id = $request->edit_next_stage;
            }

            $farmer_crop->status = $request->edit_status;
            $tracker->status = $request->edit_status;
            $tracker->start_date = $request->edit_start_date;

            $farmer_crop_id = $farmer_crop->id;
            $updated_dates = DB::select("SELECT max(last_date) AS last_date, min(start_date) start_date FROM 
                                               farmer_crop_progress_trackers WHERE farmer_crop_id = '$farmer_crop_id' 
                                                GROUP BY farmer_crop_id")[0];
            $farmer_crop->start_date = $updated_dates->start_date;
            $farmer_crop->last_date = $updated_dates->last_date;
            $farmer_crop->save();
            $tracker->save();
            DB::commit();
            $data = [
                'user_id' => $user->id, 'activity' => 'Updating Tracing Tracker id: ' . $tracker_id,
                'cooperative_id' => $user->cooperative->id
            ];
            event(new AuditTrailEvent($data));
            toastr()->success('Updated successfully');
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Oops! request Failed');
            return redirect()->back();
        }
    }


    public function get_farmer_crop_calendar($farmer_crop_id): \Illuminate\Http\JsonResponse
    {
        return $this->farmer_crop_calendar($farmer_crop_id);
    }

    public function farm_unit()
    {
        $user = Auth::user();
        $farm_units = FarmUnit::farmUnits($user->cooperative_id);
        return view('pages.cooperative.farm.farm-unit', compact('farm_units'));
    }

    public function add_farm_unit(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'unit' => 'required'
        ]);
        $user = Auth::user();
        $farm_unit = new FarmUnit();
        $farm_unit->name = ucwords(strtolower($request->unit));
        $farm_unit->cooperative_id = $user->cooperative->id;
        $farm_unit->save();
        $data = [
            'user_id' => $user->id, 'activity' => 'Added a new farm unit : ' . $request->unit,
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($data));
        toastr()->success('Farm Unit added successfully');
        return redirect()->route('cooperative.farm-units');
    }

    public function edit_farm_unit(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'edit_name' => 'required'
        ]);
        $user = Auth::user();
        $farm_unit = FarmUnit::findOrFail($id);
        $farm_unit->name = ucwords(strtolower($request->edit_name));
        $farm_unit->save();
        $data = [
            'user_id' => $user->id, 'activity' => 'Updated farm unit : ' . $request->unit,
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($data));
        toastr()->success('Farm Unit updated successfully');
        return redirect()->route('cooperative.farm-units');
    }

    public function get_stages_by_crop($crop_id, $type): \Illuminate\Support\Collection
    {
        $cooperative = Auth::user()->cooperative_id;
        if ($type == 1) {
            return CropCalendarStage::select('id', 'name')->where('crop_id', $crop_id)
                ->where('cooperative_id', $cooperative)->get();
        } else {
            return CropCalendarStage::select('id', 'name')->where('livestock_id', $crop_id)
                ->where('cooperative_id', $cooperative)->get();
        }
    }

    public function farm_yields()
    {
        $statuses = config('enums.farmer_crop_status')[0];
        $complete_status = $statuses[count($statuses) - 1];
        $user = Auth::user();
        $farmer_crops = FarmerCrop::farmYieldCrops($user->cooperative_id, $complete_status);
        $yields = FarmerYield::yields($user);
        $farmer_yields_livestock = $yields["livestock"];
        $farmer_yields_crop = $yields["crops"];
        $farmers = get_cooperative_farmers($user->cooperative_id);
        $units = Unit::where('cooperative_id', $user->cooperative_id)->get();
        $breeds = Breed::select('name', 'id')->where('cooperative_id', $user->cooperative_id)->get();
        $crops = Crop::where('cooperative_id', $user->cooperative_id)->get();
        return view('pages.cooperative.farm.yields', compact('farmer_crops', 'farmer_yields_livestock', 'farmer_yields_crop', 'farmers', 'units', 'breeds', 'crops'));
    }


    private function get_expected_yields($type, $id, $volume_indicator)
    {
        $query = FarmerExpectedYield::select('id');
        $expected_yield_id = null;
        if ($type == 'livestock') {
            $results = $query->where('livestock_breed_id', $id)->where('volume_indicator', $volume_indicator)->first();
            if ($results) {
                $expected_yield_id = $results->id;
            }
        }

        if ($type == 'crop') {
            $results = $query->where('crop_id', $id)->first();
            if ($results) {
                $expected_yield_id = $results->id;
            }
        }
        return $expected_yield_id;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function add_farmer_yield(Request $request): \Illuminate\Http\RedirectResponse
    {

        $this->validate($request, [
            'farmer' => 'required_unless:type,farm_tracker',
            'units' => 'required',
            'type' => 'required',
            'farmer_crop' => 'required_if:type,==,farm_tracker',
            'product' => 'required_unless:type,farm_tracker',
            'breed' => 'required_if:type,==,livestock',
            'crop' => 'required_if:type,==,farm',
            'date' => 'sometimes|nullable|date',
            'to_date' => 'sometimes|nullable|date',
            'yields' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'volume_indicator_count' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'volume_indicator' => 'required',
            'comments' => 'sometimes|nullable|string',
            'frequency' => 'required_if:type,==,livestock'

        ]);

        $farmer = null;
        $crop_id = null;
        $today = Carbon::now();
        if($request->to_date && $request->date == null){
            toastr()->error('Please provide afrom date  required');
            return redirect()->back()->withInput()->withErrors(["date"=>"from date is required"]);
        }
        if ($request->date ) {
            $date = Carbon::parse($request->date);
            if ($today->lt($date)) {
                toastr()->error('Please select a valid date');
                return redirect()->back()->withInput()->withErrors(["date" => "Please select a valid date"]);
            }
        }

        if(isset($date) && $request->to_date){
            $to_date = Carbon::parse($request->to_date);
            if ($today->lt($to_date)) {
                toastr()->error('Please select a valid to_date');
                return redirect()->back()->withInput()->withErrors(["to_date" => "Please select a valid to_date"]);
            }
            if($to_date->lt($date)){
                toastr()->error('end date is is more than start date');
                return redirect()->back()->withInput()->withErrors(["date"=>"is more than to_date", "to_date" => "end date is less than start date"]);
            }
        }


        if ($request->type == 'farm_tracker') {
            $farmer_crop = FarmerCrop::findOrFail($request->farmer_crop);
            $farmer = $farmer_crop->farmer_id;
            $request->product = null;
            $request->breed = null;
            $request->crop = null;
            $item_id = $farmer_crop->crop_id;
            $crop_id = $farmer_crop->crop_id;
        }


        if ($request->type == 'farm' || $request->type == 'livestock') {
            $request->farmer_crop = null;
            $farmer = $request->farmer;
            $item_id = $request->type == 'farm' ? $request->crop : $request->breed;
            $crop_id = $request->crop;
        }

        $user = Auth::user();
        $expected_yield_type = $request->type == 'livestock' ? $request->type : 'crop';
        $expected_yield_id = $this->get_expected_yields($expected_yield_type, $item_id, $request->volume_indicator);

        if (is_null($expected_yield_id)) {
            toastr()->error('Please set the expected yields for this product first');
            return redirect()->back()->withInput();
        }

        $yield = new FarmerYield();
        $yield->farmer_id = $farmer;
        $yield->type = $request->type;
        $yield->crop_id = $crop_id;
        $yield->livestock_breed_id = $request->breed;
        $yield->product = $request->product;
        $yield->date = $request->date ? Carbon::parse($request->date)->format('Y-m-d') : null;
        $yield->to_date = $request->to_date ? Carbon::parse($request->to_date)->format('Y-m-d') : null;
        $yield->expected_yields_id = $expected_yield_id;
        $yield->volume_indicator_count = $request->volume_indicator_count;
        $yield->yields = $request->yields;
        $yield->unit_id = $request->units;
        $yield->comments = $request->comments;
        $yield->cooperative_id = $user->cooperative_id;
        $yield->frequency_type = $request->frequency;
        $yield->save();
        $data = [
            'user_id' => $user->id, 'activity' => 'Registered Farmer  : ' . $request->farmer . ' yield',
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($data));
        toastr()->success('Yield Recorded successfully');
        return redirect()->route('cooperative.farmers-yields');
    }

    public function edit_yield(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'edit_date' => 'sometimes|nullable|date',
            'edit_to_date' => 'sometimes|nullable|date',
            'edit_yields' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'edit_comments' => 'sometimes|nullable|string',
            'edit_volume_indicator_count' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $today = Carbon::now();
        $date = Carbon::parse($request->edit_date);

        if($request->edit_to_date && $request->edit_date == null){
            toastr()->error('Please provide from date  required');
            return redirect()->back()->withInput()->withErrors(["date"=>"from date is required"]);
        }

        if ($today->lt($date)) {
            toastr()->error('Please select a valid date');
            return redirect()->back()->withInput()->withErrors(["edit_date" => "Please select a valid date"]);
        }

        if($request->edit_to_date and isset($date)){
            $to_date = Carbon::parse($request->edit_to_date);
            if ($today->lt($to_date)) {
                toastr()->error('Please select a valid to_date');
                return redirect()->back()->withInput()->withErrors(["to_date" => "Please select a valid to_date"]);
            }
            if($to_date->lt($date)){
                toastr()->error('end date is is more than start date');
                return redirect()->back()->withInput()->withErrors(["date"=>"is more than to_date", "to_date" => "end date is less than start date"]);
            }
        }


        $user = Auth::user();
        $yield = FarmerYield::findOrFail($id);
        $yield->date = $request->edit_date != null ? Carbon::parse($request->edit_date)->format('Y-m-d') : null;
        $yield->to_date = $request->edit_to_date != null ? Carbon::parse($request->edit_to_date)->format('Y-m-d') : null;
        $yield->frequency_type = $request->edit_frequency;
        $yield->yields = $request->edit_yields;
        $yield->comments = $request->edit_comments;
        $yield->volume_indicator_count = $request->edit_volume_indicator_count;
        $yield->save();
        $data = [
            'user_id' => $user->id, 'activity' => 'Updated Farmer  : ' . $request->edit_farmer . ' yield',
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($data));
        toastr()->success('Yield Updated successfully');
        return redirect()->route('cooperative.farmers-yields');
    }

    public function delete_yields($id): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        $yield = FarmerYield::findOrFail($id);
        $yield->forceDelete();
        $data = [
            'user_id' => $user->id, 'activity' => 'Deleted yield id   : ' . $id,
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($data));
        toastr()->warning('Yield Deleted!!');
        return redirect()->route('cooperative.farmers-yields');
    }

    public function configure_expected_yields()
    {
        $user = Auth::user();
        $expected_yields = FarmerExpectedYield::expectedYields($user->cooperative_id);
        $crops = Crop::where('cooperative_id', $user->cooperative_id)->get();
        $breeds = Breed::select('name', 'id')->where('cooperative_id', $user->cooperative_id)->get();
        $farm_units = FarmUnit::farmUnits($user->cooperative_id);
        return view("pages.cooperative.farm.expected-yields", compact('expected_yields', 'crops', 'breeds', 'farm_units'));
    }

    public function add_expected_yield_config(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'crop' => 'sometimes|nullable|string',
            'breed' => 'sometimes|nullable|string',
            'farm_unit' => 'required',
            'type' => 'required',
            'volume_indicator' => 'required',
            'quantity' => 'sometimes|nullable|regex:/^\d+(\.\d{1,2})?$/',

        ]);

        $user = Auth::user();

        if ($request->type == 'farm') {
            if ($request->crop == null) {
                toastr()->error('Please select a crop');
                return redirect()->back()->withInput()->withErrors(['crop' => "Please select a crop"]);
            }
            $request->breed = null;
        }

        if ($request->type == 'breed') {
            if ($request->breed == null) {
                toastr()->error('Please select a breed');
                return redirect()->back()->withInput()->withErrors(['livestock' => "Please select a livestock"]);
            }
            $request->crop = null;
        }

        $config = new FarmerExpectedYield();
        $config->livestock_breed_id = $request->breed;
        $config->crop_id = $request->crop;
        $config->quantity = $request->quantity;
        $config->farm_unit_id = $request->farm_unit;
        $config->volume_indicator = $request->volume_indicator;
        $config->cooperative_id = $user->cooperative_id;
        $config->save();
        $data = [
            'user_id' => $user->id, 'activity' => 'Added Expected yield configuration  : ' . $config->id,
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($data));
        toastr()->success('Configuration Added  successfully');
        return redirect()->route('cooperative.configure-expected-yields');
    }

    public function edit_expected_yield_config(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'edit_crop' => 'sometimes|nullable|string',
            'edit_breed' => 'sometimes|nullable|string',
            'edit_farm_unit' => 'required',
            'edit_volume_indicator' => 'required',
            'edit_quantity' => 'regex:/^\d+(\.\d{1,2})?$/',

        ]);

        $user = Auth::user();

        $config = FarmerExpectedYield::findOrFail($id);
        $config->livestock_breed_id = $request->edit_breed;
        $config->crop_id = $request->edit_crop;
        $config->quantity = $request->edit_quantity;
        $config->farm_unit_id = $request->edit_farm_unit;
        $config->volume_indicator = $request->edit_volume_indicator;
        $config->save();
        $data = [
            'user_id' => $user->id, 'activity' => 'Updated Expected yield configuration  : ' . $config->id,
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($data));
        toastr()->success('Configuration Updated  successfully');
        return redirect()->route('cooperative.configure-expected-yields');
    }

    public function export_farmer_calendar($type)
    {
        $user = Auth::user();
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('FarmerCalendar_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new FarmerCalendarExport($user), $file_name);
        } else {
            $data = [
                'title' => 'Farmer Calendar',
                'pdf_view' => 'farmercalender',
                'records' => FarmerCrop::farmerCrops($user, 0),
                'filename' => strtolower('FarmerCalendar_' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function export_farm_crops_details($type)
    {
        $user = Auth::user();
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('crop_details_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new FarmCropExport($user->cooperative_id), $file_name);
        } else {
            $data = [
                'title' => 'Registered Crops',
                'pdf_view' => 'crop_details',
                'records' => Crop::crops($user->cooperative_id),
                'filename' => strtolower('crop_details_' . date('d_m_Y')),
                'orientation' => 'portrait'
            ];
            return download_pdf($data);
        }
    }
}
