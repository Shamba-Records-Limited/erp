<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Http\Controllers\Controller;
use App\MillerWarehouse;
use App\MillerWarehouseAdmin;
use App\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;
use Spatie\Permission\Models\Role;

class WarehousesController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $warehouses = [];
        $user = Auth::user();
        $miller_id = null;
        if ($user->miller_admin) {
            $miller_id = $user->miller_admin->miller_id;
        }


        $warehouses = DB::select(DB::raw("
                SELECT
                    w.*
                FROM miller_warehouse w
                WHERE w.miller_id = :miller_id
            "), ["miller_id" => $miller_id]);


        return view('pages.miller-admin.warehouses.index', compact('user', 'warehouses'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $miller_id = null;
        if ($user->miller_admin) {
            $miller_id = $user->miller_admin->miller_id;
        }

        $this->validate($request, [
            "name" => "required|string|unique:miller_warehouse,name",
            "location" => "required|string",
            'f_name' => 'required|string',
            'o_names' => 'required|string',
            'user_email' => 'required|email|unique:users,email',
            'u_name' => 'required|unique:users,username',
        ]);

        try {
            DB::beginTransaction();

            // warehouse
            $warehouse = new MillerWarehouse();
            $warehouse->miller_id = $miller_id;
            $warehouse->name = $request->name;
            $warehouse->location = $request->location;
            $warehouse->save();

            // user
            $userPassword = generate_password();
            $hashedPassword = Hash::make($userPassword);

            $user = new User();
            $user->username = $request->u_name;
            $user->first_name = $request->f_name;
            $user->other_names = $request->o_names;
            $user->email = $request->user_email;
            $user->password = $hashedPassword;
            $user->save();

            // miller warehouse admin
            $warehouseAdmin = new MillerWarehouseAdmin();
            $warehouseAdmin->miller_warehouse_id = $warehouse->id;
            $warehouseAdmin->user_id = $user->id;
            $warehouseAdmin->save();


            //get roles
            $role = Role::select('id', 'name')->where('name', '=', 'miller warehouse admin')->first();
            $new_user = $user->refresh();
            $new_user->assignRole($role->name);


            DB::commit();
            toastr()->success('Warehouse Created Successfully');
            return redirect()->route('miller-admin.warehouses.show')->withInput();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }
}
