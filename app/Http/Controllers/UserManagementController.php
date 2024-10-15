<?php

namespace App\Http\Controllers;

use App\AuditTrail;
use App\CoopEmployee;
use App\CooperativeInternalRole;
use App\Events\AuditTrailEvent;
use App\Exports\AuditLogExport;
use App\InternalRolePermission;
use App\InternalUserPermission;
use App\SystemModule;
use App\SystemSubmodule;
use App\User;
use Auth;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserManagementController extends Controller
{

    const EMPLOYEE_ROLE_ID = 7;
    const COOP_ADMIN_ROLE_ID = 2;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function roles()
    {
        $user = Auth::user();
        $roles = CooperativeInternalRole::where('cooperative_id', $user->cooperative_id)->get();
        return view('pages.cooperative.user-management.roles', compact('roles'));
    }

    public function add_role(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'role' => 'required|string'
        ]);
        $user = Auth::user();
        $role = new CooperativeInternalRole();
        $role->role = $request->role;
        $role->cooperative_id = $user->cooperative_id;
        $role->save();
        $data = ['user_id' => $user->id, 'activity' => 'created  ' . $request->role . ' Internal Role', 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Role Created Successfully');
        return redirect()->back();
    }

    public function edit_role($id, Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'edit_role' => 'required|string'
        ]);
        $user = Auth::user();
        $role = CooperativeInternalRole::find($id);
        $role->role = $request->edit_role;
        $role->save();
        $data = ['user_id' => $user->id, 'activity' => 'Updated  ' . $request->edit_role . ' Internal Role', 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Role Updated Successfully');
        return redirect()->back();
    }

    /**
     * @throws \Exception
     */
    public function delete_role($id): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        $role = CooperativeInternalRole::find($id);
        $role->delete();
        $data = ['user_id' => $user->id, 'activity' => 'Deleted  ' . $role->role . ' Internal Role', 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->warning('Role was deleted');
        return redirect()->back();
    }

    public function role_management()
    {
        $user = Auth::user();
        $employees = CoopEmployee::join('users', 'users.id', '=', 'coop_employees.user_id')
            ->where('users.cooperative_id', $user->cooperative_id)->get();
        $roles = CooperativeInternalRole::where('cooperative_id', $user->cooperative_id)->get();
        return view('pages.cooperative.user-management.role-management', compact('roles', 'employees'));
    }

    /**
     * @throws ValidationException
     */
    public function assign_roles(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'roles' => 'required',
            'employee' => 'required'
        ]);
        $user = Auth::user();
        $emp = User::find($request->employee);
        $emp->cooperative_roles()->attach($request->roles);
        $data = ['user_id' => $user->id, 'activity' => 'Assigned roles to user id: ' . $request->employee, 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Roles assigned successful');
        return redirect()->back();
    }

    public function revoke_role($employee, $role): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        $emp = User::find($employee);
        $emp->cooperative_roles()->detach(array($role));
        $data = ['user_id' => $user->id, 'activity' => 'Revoked role:  ' . $role . ' from user: ' . $employee, 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Role Revoked');
        return redirect()->back();
    }


    public function module_management()
    {
        $modules = SystemModule::all();
        $user = Auth::user();
        $roles = CooperativeInternalRole::where('cooperative_id', $user->cooperative_id)->get();
        return view('pages.cooperative.user-management.modules', compact('roles', 'modules'));
    }

    /**
     * @throws \Throwable
     * @throws ValidationException
     */
    public function module_assign_role(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'roles' => 'required',
            'module' => 'required'
        ]);
        $user = Auth::user();
        $module = SystemModule::find($request->module);
        $module->cooperative_roles()->attach($request->roles, ['cooperative_id' => $user->cooperative_id]);
        $data = ['user_id' => $user->id, 'activity' => 'Assigned roles to  Module: ' . $request->module, 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Roles assigned successful');
        return redirect()->back();
    }

    public function module_revoke_role($module, $role): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        $module = SystemModule::find($module);
        $module->cooperative_roles()->detach(array($role));
        $data = ['user_id' => $user->id, 'activity' => 'Revoked role:  ' . $role . ' from Module: ' . $module, 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Role Revoked');
        return redirect()->back();
    }

    public function getSubmodulesByModuleId($moduleId): \Illuminate\Support\Collection
    {
        return SystemSubmodule::select('id', 'name')->where('module_id', $moduleId)->get();
    }

    public function getPermissions()
    {
        $user = Auth::user();
        $employees = CoopEmployee::select('users.id', 'users.first_name', 'users.other_names')
            ->join('users', 'users.id', '=', 'coop_employees.user_id')
            ->where('users.cooperative_id', '=', $user->cooperative_id)
            ->get();
        $modules = SystemModule::all();
        $permissions = InternalUserPermission::where('cooperative_id', $user->cooperative_id)->get();

        return view('pages.cooperative.user-management.sub-modules-permissions', compact('employees', 'permissions', 'modules'));
    }

    public function addPermissions(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'employee' => 'required',
            'submodule' => 'required',
            'permissions' => 'required'
        ]);

        $employeePermissionsSet = InternalUserPermission::where('employee_id', $request->employee)
                ->where('submodule_id', $request->submodule)->count() > 0;
        if ($employeePermissionsSet) {
            toastr()->error('The permission of submodules is set for this employee. Just update');
            return redirect()->back();
        }
        $user = Auth::user();
        $permission = new InternalUserPermission();
        $permission->employee_id = $request->employee;
        $permission->submodule_id = $request->submodule;
        $permission->cooperative_id = $user->cooperative_id;
        $permission->created_by_user_id = $user->id;
        $thePermissions = $request->permissions;


        if (in_array(InternalUserPermission::CAN_VIEW, $thePermissions)) {
            foreach ($thePermissions as $key => $p) {
                $permission->can_view = $p == InternalUserPermission::CAN_VIEW ? 1 : 0;
                array_splice($thePermissions, $key, 1);
                break;
            }
        }

        if (in_array(InternalUserPermission::CAN_CREATE, $thePermissions)) {
            foreach ($thePermissions as $key => $p) {
                $permission->can_create = $p == InternalUserPermission::CAN_CREATE ? 1 : 0;
                array_splice($thePermissions, $key, 1);
                break;
            }
        }
        if (in_array(InternalUserPermission::CAN_EDIT, $thePermissions)) {
            foreach ($thePermissions as $key => $p) {
                $permission->can_edit = $p == InternalUserPermission::CAN_EDIT ? 1 : 0;
                array_splice($thePermissions, $key, 1);
                break;
            }
        }
        if (in_array(InternalUserPermission::CAN_DELETE, $thePermissions)) {
            foreach ($thePermissions as $key => $p) {
                $permission->can_delete = $p == InternalUserPermission::CAN_DELETE ? 1 : 0;
                array_splice($thePermissions, $key, 1);
                break;
            }
        }
        if (in_array(InternalUserPermission::CAN_DOWNLOAD_REPORT, $thePermissions)) {
            foreach ($thePermissions as $key => $p) {
                $permission->can_download_report = $p == InternalUserPermission::CAN_DOWNLOAD_REPORT ? 1 : 0;
                array_splice($thePermissions, $key, 1);
                break;
            }
        }
        $permission->save();
        $data = [
            'user_id' => $user->id,
            'activity' => 'Assigned ' . json_encode($thePermissions) . ' for sub module ' . $request->submodule . ' to employee ' . $request->employee,
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($data));
        toastr()->success('Permission created successfully');
        return redirect()->back();
    }

    public function editPermissions(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        $permission = InternalUserPermission::findOrFail($id);
        $permission->can_view = $request->view ? 1 : 0;
        $permission->can_create = $request->create ? 1 : 0;
        $permission->can_delete = $request->delete ? 1 : 0;
        $permission->can_edit = $request->edit ? 1 : 0;
        $permission->can_download_report = $request->download ? 1 : 0;
        $permission->updated_at = Carbon::now();
        $permission->updated_by_user_id = $user->id;
        $permission->save();

        $data = [
            'user_id' => $user->id,
            'activity' => 'Updated permission  ' . $id . ' to ' . json_encode($request->all()),
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($data));
        toastr()->success('Permission updated successfully');
        return redirect()->back();
    }

    public function rolePermission()
    {
        $user = Auth::user();
        $roles = CooperativeInternalRole::select('id', 'role')->where('cooperative_id', $user->cooperative_id)->get();
        $permissions = InternalRolePermission::where('cooperative_id', $user->cooperative_id)->get();
        return view('pages.cooperative.user-management.role-permissions', compact('roles', 'permissions'));
    }

    public function addRolePermission(Request $request): \Illuminate\Http\RedirectResponse
    {

        $this->validate($request, [
            'role' => 'required',
            'permissions' => 'required'
        ]);

        $thePermissions = $request->permissions;
        $user = Auth::user();

        $rolePermission = new InternalRolePermission();
        $rolePermission->internal_role_id = $request->role;
        $rolePermission->cooperative_id = $user->cooperative_id;
        $rolePermission->created_by_user_id = $user->id;
        if (in_array(InternalUserPermission::CAN_VIEW, $thePermissions)) {
            foreach ($thePermissions as $key => $p) {
                $rolePermission->can_view = $p == InternalUserPermission::CAN_VIEW ? 1 : 0;
                array_splice($thePermissions, $key, 1);
                break;
            }
        }

        if (in_array(InternalUserPermission::CAN_CREATE, $thePermissions)) {
            foreach ($thePermissions as $key => $p) {
                $rolePermission->can_create = $p == InternalUserPermission::CAN_CREATE ? 1 : 0;
                array_splice($thePermissions, $key, 1);
                break;
            }
        }
        if (in_array(InternalUserPermission::CAN_EDIT, $thePermissions)) {
            foreach ($thePermissions as $key => $p) {
                $rolePermission->can_edit = $p == InternalUserPermission::CAN_EDIT ? 1 : 0;
                array_splice($thePermissions, $key, 1);
                break;
            }
        }
        if (in_array(InternalUserPermission::CAN_DELETE, $thePermissions)) {
            foreach ($thePermissions as $key => $p) {
                $rolePermission->can_delete = $p == InternalUserPermission::CAN_DELETE ? 1 : 0;
                array_splice($thePermissions, $key, 1);
                break;
            }
        }
        if (in_array(InternalUserPermission::CAN_DOWNLOAD_REPORT, $thePermissions)) {
            foreach ($thePermissions as $key => $p) {
                $rolePermission->can_download_report = $p == InternalUserPermission::CAN_DOWNLOAD_REPORT ? 1 : 0;
                array_splice($thePermissions, $key, 1);
                break;
            }
        }
        $rolePermission->save();
        $data = [
            'user_id' => $user->id,
            'activity' => 'Permissions added for role:  ' . $request->role,
            'cooperative_id' => $user->cooperative->id
        ];

        event(new AuditTrailEvent($data));
        toastr()->success('Permission added successfully');
        return redirect()->back();
    }

    public static function editRolePermission(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        $permission = InternalRolePermission::findOrFail($id);
        $permission->can_view = $request->view ? 1 : 0;
        $permission->can_create = $request->create ? 1 : 0;
        $permission->can_delete = $request->delete ? 1 : 0;
        $permission->can_edit = $request->edit ? 1 : 0;
        $permission->can_download_report = $request->download ? 1 : 0;
        $permission->updated_at = Carbon::now();
        $permission->updated_by_user_id = $user->id;
        $permission->save();

        $data = [
            'user_id' => $user->id,
            'activity' => 'Updated permission  ' . $id . ' to ' . json_encode($request->all()),
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($data));
        toastr()->success('Permission updated successfully');
        return redirect()->back();
    }

    public function activityLog(Request $request)
    {
        $user = Auth::user();
        $cooperative = $user->cooperative_id;
        $coop_admin_role_id = self::COOP_ADMIN_ROLE_ID;
        $emp_role_id = self::EMPLOYEE_ROLE_ID;
        $employees = DB::select("
            select u.id,u.first_name, u.other_names from users u
            inner join model_has_roles mhr  on u.id = mhr.model_id
            where u.cooperative_id = '$cooperative' and mhr.role_id in ('$emp_role_id','$coop_admin_role_id');
        ");

        $auditTrails = AuditTrail::auditTrails($request, $cooperative);
        return view('pages.cooperative.user-management.activity_log', compact('auditTrails', 'employees'));
    }

    public function export_audit_logs($type, $employee = null, $dates = null)
    {
        $cooperative = Auth::user()->cooperative;

        $title = 'Audit log ';
        $file_name_formatted = 'audit_log_export';
        $requestArr = [];

        if ($employee != null) {
            $requestArr['employee'] = $employee;
        }

        if ($dates != null) {
            $requestArr['dates'] = $dates;
        }

        $request = Request::create(
            '',
            'GET',
            $requestArr
        );

        if ($employee) {
            $employee = User::findOrFail($employee);
            $raw_name = $employee->first_name . ' ' . $employee->other_names;
            $name = ucwords(strtolower($raw_name));

            $title .= "For $name ";
            $file_name_formatted .= '_' . str_replace(' ', '_', strtolower($raw_name));
        }

        if ($dates) {
            $dates_array = explode(" - ", $dates);
            $from = Carbon::parse(trim($dates_array[0]))->format('Y-m-d');
            $to = Carbon::parse(trim($dates_array[1]))->format('Y-m-d');

            $title .= "Between $from and $to";
            $dates = sprintf("%s_%s", $from, $to);
            $file_name_formatted .= '_' . str_replace('-', '_', $dates);
        }

        if ($type != env('PDF_FORMAT')) {
            $file_name = $file_name_formatted . '_' . date('d_m_Y') . '.' . $type;
            return Excel::download(new AuditLogExport($cooperative->id, $request), $file_name);
        } else {

            $data = [
                'title' => $title,
                'pdf_view' => 'audit_logs',
                'records' => AuditTrail::auditTrails($request, $cooperative->id, true),
                'filename' => $file_name_formatted . '_' . date('d_m_Y'),
                'orientation' => 'landscape'
            ];
            return deprecated_download_pdf($data);
        }
    }


    public function change_password()
    {
        return view('pages.cooperative.user-management.change-password');
    }

    public function update_password(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed|regex:/^(?=.*\d)(?=.*[a-zA-Z])(?=.*[-\#\$\.\%\&\*\@])(?=.*[a-zA-Z]).{8,16}$/',
        ]);

        $user = Auth::user();

        if (password_verify($request->old_password, $user->password)) {
            $user->password = bcrypt($request->password);
            $user->updated_at = Carbon::now();
            $user->save();

            toastr()->success('Passsword updated successfully');
            $data = [
                'user_id' => $user->id,
                'activity' => 'Updated password',
                'cooperative_id' => $user->cooperative->id
            ];
            event(new AuditTrailEvent($data));

            return redirect()->back();
        }

        return redirect()->back()->withErrors(['old_password' => 'id not match the existing password']);
    }
}
