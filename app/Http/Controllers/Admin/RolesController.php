<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;
use Log;

class RolesController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $roles = DB::select(DB::raw("
                SELECT r.id, r.name
                FROM roles r;
            "));

        return view('pages.admin.roles.index', compact('roles'));
    }

    public function detail_permissions($id)
    {
        $roles = DB::select(DB::raw("
                SELECT r.id, r.name
                FROM roles r
                WHERE r.id = :id;
            "), ["id" => $id]);

        $role = null;
        if (count($roles) > 0){
            $role = $roles[0];
        }

        return view('pages.admin.roles.detail-tabs.permissions', compact('role', 'id'));
    }

    public function detail_users($id)
    {
        $roles = DB::select(DB::raw("
                SELECT r.id, r.name
                FROM roles r
                WHERE r.id = :id;
            "), ["id" => $id]);

        $role = null;
        if (count($roles) > 0){
            $role = $roles[0];
        }


        return view('pages.admin.roles.detail-tabs.users', compact('role', 'id'));
    }
}
