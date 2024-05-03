<?php

namespace App\Http\Controllers;

use App\Disease;
use App\Events\AuditTrailEvent;
use App\Exports\ReportedCasesExport;
use App\Farmer;
use App\ReportedCase;
use App\User;
use App\Vet;
use App\VetBooking;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class ReportedCasesController extends Controller
{
    use \App\Http\Traits\Disease;

    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $user = \Auth::user();
        $farmers = Farmer::select('users.first_name', 'users.other_names', 'users.id')
            ->join('users', 'users.id', '=', 'farmers.user_id')
            ->where('users.cooperative_id', $user->cooperative_id)
            ->orderBy('users.first_name')
            ->orderBy('users.other_names')
            ->get();
        $diseases = Disease::diseases($user->cooperative_id);
        $reported_cases = ReportedCase::cases($user);

        return view('pages.cooperative.disease.reported_cases', compact('farmers', 'diseases', 'reported_cases'));

    }

    public function add_case(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'farmer' => 'required',
            'disease' => 'required',
            'status' => 'required',
            'symptoms' => 'required',
        ]);
        $user = Auth::user();
        $this->saveCase(new ReportedCase, $request, $request->farmer, $user->cooperative_id);
        $data = ['user_id' => $user->id, 'activity' => 'Added a disease case for disease ' . $request->disease . ' To farmer ' . $request->farmer, 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Case added successfully');
        return redirect()->back();
    }

    public function edit_case(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'edit_farmer' => 'required',
            'edit_disease' => 'required',
            'edit_status' => 'required',
            'edit_symptoms' => 'required',
        ]);

        $user = Auth::user();
        $reported_case = ReportedCase::findOrFail($id);
        $this->saveCase($reported_case, $request, $request->edit_farmer, $user->cooperative_id, true);
        $data = ['user_id' => $user->id, 'activity' => 'Edit a disease case  ' . $reported_case->id . ' To farmer ' . $request->edit_farmer, 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Case edited successfully');
        return redirect()->back();
    }

    public function book_vet($id)
    {
        $user = Auth::user();
        $vets = Vet::vets($user->cooperative_id);
        return view('pages.cooperative.disease.case-booking', compact('vets', 'id'));
    }

    public function book(Request $request, $id)
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

    public function export_reported_cases($type)
    {
        $user = Auth::user();
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('disease_reported_cases_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new ReportedCasesExport($user), $file_name);
        } else {
            $data = [
                'title' => 'Disease Reported Cases',
                'pdf_view' => 'reported_cases',
                'records' => ReportedCase::cases($user),
                'filename' => strtolower('disease_reported_cases_' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

}
