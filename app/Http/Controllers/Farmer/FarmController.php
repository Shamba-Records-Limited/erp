<?php

namespace App\Http\Controllers\Farmer;

use App\Breed;
use App\Cow;
use App\Crop;
use App\CropCalendarStage;
use App\Events\AuditTrailEvent;
use App\FarmerCrop;
use App\FarmerCropProgressTracker;
use App\FarmerExpectedYield;
use App\FarmerYield;
use App\FarmUnit;
use App\Http\Controllers\Controller;
use App\Http\Traits\Farm;
use App\Http\Traits\FarmManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmController extends Controller
{
    use Farm, FarmManagement;

    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function cows()
    {
        $farmer = Auth::user()->farmer->id;
        $livestock = Cow::where('farmer_id', $farmer)->with('farmer')->latest()->get();
        $breeds = Breed::breeds();
        return view('pages.as-farmer.farm.livestock-poultry', compact('livestock', 'breeds'));
    }

    public function add_livestock(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            "name" => "required|string",
            "breed_id" => "required|string",
            "tag_name" => "sometimes|nullable|string",
            "animal_type" => "required",
        ]);
        $user = Auth::user();
        if ($request->tag_name) {
            if (tag_name_already_exists($request->tag_name, $user->cooperative_id, 1)) {
                toastr()->error('Tag name is already used');
                return redirect()->back()
                    ->withInput()
                    ->withErrors(["tag_name" => "tag name is already provided"]);
            }
        }

        if (Cow::persist($request, $user->cooperative->id, $user->farmer->id, Cow::APPROVAL_STATUS_PENDING, new Cow())) {
            $data = ['user_id' => $user->id, 'activity' => 'created  ' . $request->name . ' Livestock', 'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Animal Added Successfully');
            return redirect()->back();
        }
        toastr()->success('Oops operation failed');
        return redirect()->back()->withInput();
    }

    public function breeds()
    {
        $breeds = Breed::breeds();
        return view('pages.as-farmer.farm.breed', compact('breeds'));
    }

    public function farm_units()
    {
        $farm_units = FarmUnit::farmUnits(Auth::user()->cooperative_id);
        return view('pages.as-farmer.farm.farm-unit', compact('farm_units'));
    }

    public function crops()
    {
        $crops = Crop::crops(Auth::user()->cooperative_id);
        return view('pages.as-farmer.farm.crop', compact('crops'));
    }

    public function crop_calendar_stages()
    {
        $user = Auth::user();
        $cropCalendarStages = CropCalendarStage::stages($user->cooperative_id);
        return view('pages.as-farmer.farm.crop-calendar-stages', compact('cropCalendarStages'));
    }

    public function farmer_crops()
    {
        $user = Auth::user();
        $farmer_crops = FarmerCrop::farmerCrops($user);
        $crops = Crop::where('cooperative_id', $user->cooperative_id)->get();
        $stages = CropCalendarStage::where('cooperative_id', $user->cooperative_id)->get();
        $livestock = Cow::select('cows.id', 'cows.name', 'cows.animal_type', 'breeds.name as breed')
            ->join('breeds', 'breeds.id', '=', 'cows.breed_id')
            ->where('cows.farmer_id', $user->farmer->id)->get();
        return view('pages.as-farmer.farm.farmer-crop', compact('farmer_crops', 'stages', 'crops', 'livestock'));
    }

    public function add_farmer_crop(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'type' => 'required',
            'crop' => 'required_if:type,==,1',
            'livestock' => 'required_if:type,==,2',
            'stage' => 'required|different:next_stage',
            'next_stage' => 'sometimes|nullable|different:stage',
            'cost' => 'sometimes|nullable',
            'status' => 'required',
            'start_date' => 'sometimes|nullable|date',
        ]);

        return $this->add_farmer_farm_crop($request, true);
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

        return view('pages.as-farmer.farm.farmer-crop-trackers', compact('trackers', 'farmer_crop_id', 'stages'));
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

    public function get_cost_break_down($tracker_id)
    {
        return $this->cost_break_down($tracker_id, 'as-farmer.farm.cost-break-downs');
    }

    public function get_farmer_crop_calendar($farmer_crop_id): \Illuminate\Http\JsonResponse
    {
        return $this->farmer_crop_calendar($farmer_crop_id);
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

    public function expected_yields()
    {
        $expected_yields = FarmerExpectedYield::expectedYields(Auth::user()->cooperative_id);
        return view("pages.as-farmer.farm.expected-yields", compact('expected_yields'));
    }

    public function yields()
    {
        $yields = FarmerYield::yields(Auth::user());
        $farmer_yields_livestock = $yields["livestock"];
        $farmer_yields_crop = $yields["crops"];
        return view('pages.as-farmer.farm.yields', compact('farmer_yields_livestock', 'farmer_yields_crop'));
    }
}
