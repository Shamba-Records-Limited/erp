<?php

namespace App\Http\Controllers;

use App\Breed;
use App\Events\AuditTrailEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BreedController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $breeds = Breed::breeds();
        return view('pages.cooperative.farm.breed', compact('breeds'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            "name" => "required|string",
        ]);
        $breed = new Breed();
        $this->persist($request, $breed, $user->cooperative->id);
        $data = ['user_id' => $user->id, 'activity' => 'created  ' . $request->name . ' Breed', 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Breed Created Successfully');
        return redirect()->route('cooperative.farm.breeds');
    }


    private function persist($request, $breed, $cooperative)
    {
        $breed->name = $request->name;
        $breed->cooperative_id = $cooperative;
        $breed->save();
    }

    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            "edit_name" => "required|string",
        ]);
        $user = Auth::user();
        $breed = Breed::findOrFail($id);
        $req = (object)["name" => $request->edit_name];
        $this->persist($req, $breed, $user->cooperative->id);
        $data = ['user_id' => $user->id, 'activity' => 'Updated  ' . $breed->name . ' Breed', 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Breed Updated Successfully');
        return redirect()->route('cooperative.farm.breeds');

    }


}
