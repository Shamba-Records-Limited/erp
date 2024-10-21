<?php

namespace App\Http\Controllers;

use App\Events\AuditTrailEvent;
use App\VetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VetServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $vet_services = VetService::where('cooperative_id', Auth::user()->cooperative->id)->latest()->get();
        return view('pages.cooperative.vets.service.index', compact('vet_services'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request,[
            "service_name" => "required|string",
            "service_description" => "required|string",
            "type" => "required|string",
        ]);
        $user = Auth::user();
        $service = new VetService();
        $this->save($request, $service, $user);
        $data = ['user_id' => $user->id, 'activity' => 'created  '.$request->service_name.' vet service','cooperative_id'=> $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Service Created Successfully');
        return redirect()->route('cooperative.vet.service.show');
    }

    private function save($request, $service, $user){
        $service->name = $request->service_name;
        $service->description = $request->service_description;
        $service->type = $request->type;
        $service->cooperative_id = $user->cooperative->id;
        $service->save();
    }
}
