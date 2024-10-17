<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesNPermissionsContoller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('rolesnpermission',compact('roles','permissions'));
    }

    public function admin_roles()
    {

        $user = User::where("username","=","admin")->first();

        if($user !== null)
        {
            $role = Role::select('id','name')->first();
            $user->assignRole($role->name);

        }


        return redirect()->route('home');
    }

    public function assign_permission(Request $request)
    {
        $this->validate($request,[
            'permission'=>'required|integer',
            'permission'=>'required|integer'
        ]);

        $role = Role::findorFail($request->role);
        $permission = Permission::findById($request->permission);
        $role->givePermissionTo($permission);

        $data = ['user_id' => Auth::user()->id, 'activity' => 'Assigned permission to roles'];
        event(new ActivityLogEvent($data));

        return redirect()->back();
    }

    public function revoke_permission($role,$permission)
    {
        $role = Role::findById($role);
        $permission = Permission::findById($permission);
        $permission->removeRole($role);

        $data = ['user_id' => Auth::user()->id, 'activity' => 'Revoked permission'];
        event(new ActivityLogEvent($data));

        return redirect()->route('roles');
    }
}
