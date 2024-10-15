<?php

namespace App\Http\Controllers;

use App\Breed;
use App\Cow;
use App\Events\AuditTrailEvent;
use App\Exports\FarmMngtLivestockPoultryExport;
use App\User;
use Excel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CowController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $cooperative_id = Auth::user()->cooperative->id;
        $breeds = Breed::where('cooperative_id', $cooperative_id)->latest()->get();
        $animals = Cow::where('cooperative_id', $cooperative_id)->where('approval_status', Cow::APPROVAL_STATUS_APPROVED)->latest()->get();
        $other_animals = Cow::where('cooperative_id', $cooperative_id)->where('approval_status', '<>',Cow::APPROVAL_STATUS_APPROVED)->latest()->get();
        $farmers = farmers($cooperative_id);
        return view('pages.cooperative.farm.animal', compact('breeds', 'animals', 'farmers', 'other_animals'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => "required|string",
            "breed_id" => "required|string",
            "tag_name" => "sometimes|nullable|string",
            "animal_type" => "required",
            "farmer_id" => "required"
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

        if (Cow::persist($request, $user->cooperative->id, $request->farmer_id,Cow::APPROVAL_STATUS_APPROVED, new Cow())) {
            $data = ['user_id' => $user->id, 'activity' => 'created  ' . $request->name . ' Cow', 'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Animal Added Successfully');
            return redirect()->back();
        }
        toastr()->success('Oops operation failed');
        return redirect()->back()->withInput();
    }


    /**
     * @throws \Throwable
     * @throws ValidationException
     */
    public function edit(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            "edit_name" => "required|string",
            "edit_breed_id" => "required|string",
            "edit_tag_name" => "sometimes|string",
            "edit_animal_type" => "required",
        ]);

        $user = Auth::user();
        if ($request->edit_tag_name) {
            if (tag_name_already_exists($request->edit_tag_name, $user->cooperative_id, 2)) {
                toastr()->error('Tag name is already used');
                return redirect()->route('cooperative.farm.animals')
                    ->withInput()
                    ->withErrors(["edit_tag_name" => "tag name is already provided"]);
            }
        }

        $req = (object)["name" => $request->edit_name, "breed_id" => $request->edit_breed_id, "tag_name" => $request->edit_tag_name, "farmer_id" => $request->edit_farmer_id, "animal_type" => $request->edit_animal_type];
        $animal = Cow::findOrFail($id);
        Cow::persist($req, $user->cooperative->id, $request->edit_farmer_id, $request->edit_approval_status, $animal);
        $data = ['user_id' => $user->id, 'activity' => 'Updated  ' . $req->name . ' Animal', 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Animal updated Successfully');
        return redirect()->route('cooperative.farm.animals');
    }

    public function export_farm_livestock_poultry($type)
    {
        $cooperative_id = Auth::user()->cooperative->id;
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('farm_management_livestock_poultry_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new FarmMngtLivestockPoultryExport($cooperative_id), $file_name);
        } else {
            $data = [
                'title' => 'Farm Management Livestock/Poultry',
                'pdf_view' => 'livestock_poultry',
                'records' => [
                    'first_record' => Cow::where('cooperative_id', $cooperative_id)->where('approval_status', Cow::APPROVAL_STATUS_APPROVED)->latest()->get(),
                    'second_record' => Cow::where('cooperative_id', $cooperative_id)->where('approval_status', '<>',Cow::APPROVAL_STATUS_APPROVED)->latest()->get(),
                ],
                'filename' => strtolower('farm_management_livestock_poultry_' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return deprecated_download_pdf($data);
        }
    }
}
