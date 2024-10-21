<?php

namespace App\Http\Controllers\Farmer;

use App\DiseaseCategory;
use App\Events\AuditTrailEvent;
use App\Http\Controllers\Controller;
use App\Http\Traits\Disease;
use App\ReportedCase;
use App\Vet;
use Auth;
use App\Disease as D;
use DB;
use Illuminate\Http\Request;
use Log;

class DiseaseController extends Controller
{
    use Disease;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard_data()
    {
        $cooperative = Auth::user()->cooperative_id;
        $disease_cases = $this->dashboard($cooperative);
        return view('pages.as-farmer.minidashboards.disease-management', compact('disease_cases'));
    }

    public function stats(): array
    {
        $cooperative = Auth::user()->cooperative_id;
        return $this->dashboard_stats($cooperative);
    }

    public function disease_map_data(): array
    {
        $cooperative = Auth::user()->cooperative_id;
        return $this->map_data($cooperative);
    }

    public function categories()
    {
        $categories = DiseaseCategory::categories(Auth::user()->cooperative->id);
        return view('pages.as-farmer.disease.category', compact('categories'));
    }

    public function disease()
    {
        $user = Auth::user();
        $diseases = D::diseases($user->cooperative->id);
        return view('pages.as-farmer.disease.disease', compact('diseases'));
    }

    public function cases()
    {
        $user = Auth::user();
        $diseases = D::diseases($user->cooperative_id);
        $reported_cases = ReportedCase::cases($user);

        return view('pages.as-farmer.disease.cases', compact('diseases', 'reported_cases'));
    }

    public function add_case(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'disease' => 'required',
            'status' => 'required',
            'symptoms' => 'required',
        ]);
        $user = Auth::user();
        $this->saveCase(new ReportedCase, $request, $user->id, $user->cooperative_id);
        $data = ['user_id' => $user->id, 'activity' => 'Added a disease case for disease ' . $request->disease . ' To farmer ' . $user->id, 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Case added successfully');
        return redirect()->back();
    }

    public function edit_case(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'edit_disease' => 'required',
            'edit_status' => 'required',
            'edit_symptoms' => 'required',
        ]);

        $user = Auth::user();
        $reported_case = ReportedCase::findOrFail($id);
        $this->saveCase($reported_case, $request, $user->id, $user->cooperative_id, true);
        $data = ['user_id' => $user->id, 'activity' => 'Edit a disease case  ' . $reported_case->id . ' To farmer ' . $user->id, 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Case updated successfully');
        return redirect()->back();
    }

    public function case_bookings($id)
    {
        $user = Auth::user();
        $vets = Vet::vets($user->cooperative_id);
        return view('pages.as-farmer.disease.case-booking', compact('vets', 'id'));
    }

    public function book(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'vet' => 'required',
            'start' => 'required',
            'duration' => 'required|integer|min:1|max:12',
        ]);
        try {

            DB::beginTransaction();
            $user = Auth::user();
            return $this->case_book_vet($request, $id, $user);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Request Failed: " . $e->getMessage());
            toastr()->error('Oops! Request Failed');
            return redirect()->back();
        }

    }
}
