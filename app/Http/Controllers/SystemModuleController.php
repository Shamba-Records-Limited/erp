<?php

namespace App\Http\Controllers;

use App\Events\AuditTrailEvent;
use App\SystemModule;
use App\SystemSubmodule;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SystemModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $modules = SystemModule::all();
        return view('pages.admin.modules', compact('modules'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'moduleSubmodules' => 'sometimes|nullable'
        ]);

        if (SystemModule::count() > 14) {
            toastr()->error('The system has a maximum of 14 modules. No additional modules Allowed. Consider editing existing modules');
            return redirect()->back();
        }
        $user = Auth::user();
        $module = new SystemModule();
        $module->name = ucwords($request->name);
        $module->save();
        if ($this->saveSubmodules(json_decode($request->moduleSubmodules), $module->refresh()->id)) {
            $data = ['user_id' => $user->id, 'activity' => 'Added new module and submodules', 'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Module created');
            return redirect()->back();
        }
        $data = ['user_id' => $user->id, 'activity' => 'Added a new module and sub modules failed', 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
       return redirect()->back();
    }

    public function edit($id, Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'edit_name' => 'required'
        ]);

        $user = Auth::user();
        $module = SystemModule::find($id);
        $module->name = ucwords($request->edit_name);
        $module->save();
        $data = ['user_id' => $user->id, 'activity' => 'Edited a module', 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Module updated');
        return redirect()->back();

    }

    public function subModule(Request $request)
    {
        if ($request->module) {
            $submodules = SystemSubmodule::where('module_id', $request->module)->get();
        } else {
            $submodules = SystemSubmodule::all();
        }
        $modules = SystemModule::select('id','name')->get();
        return view('pages.admin.submodules', compact('submodules', 'modules'));
    }

    public function addSubmodules(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            "moduleSubmodules" => 'required',
            "module" => 'required'
        ]);

        $user = Auth::user();
        if($this->saveSubmodules(json_decode($request->moduleSubmodules), $request->module)){
            $data = ['user_id' => $user->id, 'activity' => 'Added a new sub modules failed for module: '.$request->module, 'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Sub modules added successfully');
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function saveSubmodules($submodules, $moduleId): bool
    {
        try {
            DB::beginTransaction();
            foreach ($submodules as $submodule){
                $item  = new SystemSubmodule();
                $item->name = trim(ucwords(strtolower($submodule->item)));
                $item->module_id = $moduleId;
                $item->save();
            }
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            Log::error('Error while saving submodules: '.$e->getMessage());
            DB::rollBack();
            toastr()->error("Oops! Operation failed");
            return false;
        }
    }

}
