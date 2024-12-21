<?php

namespace App\Http\Controllers\Admin;

use App\Cooperative;
use App\County;
use App\CountyGovtOfficial;
use App\Events\AuditTrailEvent;
use App\Events\NewUserRegisteredEvent;
use App\Http\Controllers\Controller;
use App\SubCounty;
use App\User;
use Auth;
use DB;
use Hash;
use Illuminate\Http\Request;
use Log;
use Spatie\Permission\Models\Role;

class CountyGovtOfficialsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }


    public function index()
    {

        $officials = DB::select(DB::raw("
            SELECT official.*,
                u.username,
                c.name AS county_name,
                sub_c.name AS sub_county_name
            FROM county_govt_officials official
            JOIN users u ON official.user_id = u.id
            LEFT JOIN counties c ON c.id = official.county_id
            LEFT JOIN sub_counties sub_c ON sub_c.id = official.sub_county_id
        "));

        $cooperatives = Cooperative::all();
        $counties = County::all();
        $sub_counties = SubCounty::all();

        return view('pages.admin.county-govt-officials.index', compact('officials', 'cooperatives', 'counties', 'sub_counties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cooperative_id' => 'required|exists:cooperatives,id',
            'country_code' => 'required',
            'county_id' => 'required|exists:counties,id',
            'sub_county_id' => 'required|exists:sub_counties,id',
            'id_no' => 'required|unique:county_govt_officials,id_no',
            'phone_no' => 'required|regex:/^[0-9]{12}$/|unique:county_govt_officials,phone_no',
            'employee_no' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'first_name' => 'required|string',
            'other_names' => 'required|string',
            'gender' => 'required',
            'ministry' => 'required|string',
            'designation' => 'required|string',
            'profile_picture' => "required|image|mimes:jpeg,jpg,png,gif|max:3072",
        ]);

        // dd($request->file("profile_picture"));

        try {
            DB::beginTransaction();
            //generate password
            $password = generate_password();

            // create user
            $user = new User();
            $user->first_name = ucwords(strtolower($request->first_name));
            $user->other_names = ucwords(strtolower($request->other_names));
            //$user->cooperative_id =substr_replace($request->cooperative_id, 'CG', -2);//to avoid dupication actua coop user
            $user->email = $request->email;
            $user->username = $request->username;
            $password = generate_password();
            $user->password = Hash::make($password);
            save_user_image($user, $request);
            $user->save();

            //official...
            $official = new CountyGovtOfficial();
            $official->country_code = $request->country_code;
            $official->county_id = $request->county_id;
            $official->sub_county_id = $request->sub_county_id;
            $official->gender = $request->gender;
            $official->id_no = $request->id_no;
            $official->phone_no = $request->phone_no;
            $official->employee_no = $request->employee_no;
            $official->user_id = $user->id;
            $official->cooperative_id = $request->cooperative_id;
            $official->ministry = $request->ministry;
            $official->designation = $request->designation;
            $official->save();

            Log::debug("Saved official: $official");
            //assign role to user
            $role = Role::select('id', 'name')->where('name', '=', 'county govt official')->first();
            $user->assignRole($role->name);

            //audit trail log
            $role_created_audit = ['user_id' => $user->id, 'activity' => 'Assigned ' . $role->name .
                ' to  ' . $user->username, 'cooperative_id' => $official->cooperative_id];
            event(new AuditTrailEvent($role_created_audit));

            //send email and new audit trail
            $data = [
                "name" => ucwords(strtolower($request->first_name)) . ' ' . ucwords(strtolower($request->other_names)),
                "email" => $request->email, "password" => $password
            ];
            $audit_trail_data = [
                'user_id' => $user->id,
                'activity' => 'Created ' . $user->username . 'account',
                'cooperative_id' => $official->cooperative_id
            ];
            event(new AuditTrailEvent($audit_trail_data));
            event(new NewUserRegisteredEvent($data));

            DB::commit();
            toastr()->success('County Govt Official Created Successfully');
            return redirect()->route('admin.county-govt-officials.show');
        } catch (\Throwable $th) {
            //throw $th;
            Log::error("----------------------------------------");
            Log::error($th->getMessage());
            Log::error($th->getTraceAsString());
            DB::rollback();
            toastr()->error('County Govt Official could not be created:');
            return redirect()->back()->withInput();
        }

        return redirect()->route('admin.users.detail', $request->user_id);
    }

    public function edit($id)
    {
        // $branch = CoopBranch::find($id);
        $officials = DB::select(DB::raw("
                    SELECT 
                        u.*,
                        official.*,
                        c.name as coop_name,
                        sub_county.name AS sub_county_name
                    FROM county_govt_officials official
                    JOIN users u ON official.user_id = u.id
                    JOIN cooperatives c ON u.cooperative_id = c.id
                    LEFT JOIN sub_counties sub_county ON sub_county.id = official.sub_county_id
                    WHERE official.id = :id;
                "), ["id" => $id]);

        $official = null;
        if (count($officials) > 0) {
            $official = $officials[0];
        }

  
        $countries = get_countries();
        $cooperatives = Cooperative::all();
        $counties = County::all();
        $sub_counties = SubCounty::all();

        return view('pages.admin.county-govt-officials.edit', compact('official', 'id', 'cooperatives', 'countries', 'counties', 'sub_counties'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:county_govt_officials,id',
          //  'country_code' => 'required',
            'county_id' => 'required|exists:counties,id',
            'sub_county_id' => 'required|exists:sub_counties,id',
            'id_no' => "required|unique:county_govt_officials,id_no,$request->id",
            'phone_no' => "required|regex:/^[0-9]{12}$/|unique:county_govt_officials,phone_no,$request->id",
            'employee_no' => 'required|string',
            'gender' => 'required',
            'ministry' => 'required',
            'designation' => 'required',
            //'profile_picture' => "required|image|mimes:jpeg,jpg,png,gif|max:3072",
        ]);

        
       // dd($request->all());
        try {
            
            DB::beginTransaction();
            $user = Auth::user();

            $official = CountyGovtOfficial::find($request->id);
            $userprof = User::findOrFail($official->user_id);
            // Update user details
            $userprof->username = $request->username;
            $userprof->first_name = $request->first_name;
            $userprof->other_names = $request->other_names;
            $userprof->email = $request->email;
            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Log the file upload
                Log::info('Profile picture uploaded for user: ' . $userprof->id);
                // Delete old profile picture if it exists
                if ($userprof->profile_picture && file_exists(public_path('storage/' . $userprof->profile_picture))) {
                    unlink(public_path('storage/' . $userprof->profile_picture));
                    Log::info('Old profile picture deleted: ' . $userprof->profile_picture);
                }
                // Save the new profile picture
                $file = $request->file('profile_picture');
                $filePath = $file->store('images/profile', 'public');
                $userprof->profile_picture = $filePath;
                Log::info('New profile picture stored at: ' . $filePath);
            }
            $userprof->save();

            //official...
           // $official->country_code = $request->country_code;
            $official->county_id = $request->county_id;
            $official->sub_county_id = $request->sub_county_id;
            $official->gender = $request->gender;
            $official->id_no = $request->id_no;
            $official->phone_no = $request->phone_no;
            $official->employee_no = $request->employee_no;
            $official->ministry = $request->ministry;
            $official->designation = $request->designation;
            $official->save();

            Log::debug("Updated official: $official");

            //audit trail log
            // $role_created_audit = ['user_id' => $user->id, 'activity' => 'Updated ' . $official->user->username .
            //     ' to  ' . $user->username, 'cooperative_id' => $user->cooperative->id];
            // event(new AuditTrailEvent($role_created_audit));

            //send email and new audit trail
            $data = [
                "name" => ucwords(strtolower($request->first_name)) . ' ' . ucwords(strtolower($request->other_names)),
                "email" => $request->email
            ];
            $audit_trail_data = [
                'user_id' => $user->id,
                'activity' => 'Updated ' . $official->user->username . 'account',
                'cooperative_id' => $user->cooperative->id
            ];
            event(new AuditTrailEvent($audit_trail_data));

            DB::commit();
            toastr()->success('County Govt Official Updated Successfully');
            return redirect()->route('admin.county-govt-officials.show');
        } catch (\Throwable $th) {
            //throw $th;
            Log::error("----------------------------------------");
            Log::error($th->getMessage());
            Log::error($th->getTraceAsString());
            DB::rollback();
            toastr()->error('County Govt Official could not be updateds');
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $official = CountyGovtOfficial::findOrFail($id);
            $coop = $official->user->cooperative;
            $coop_id = $coop->id;
            $coop_name = $coop->name;
            $official->delete();
            //audit trail log
            $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Deleted county govt official ' . $official['name'] .
                ' belonging to ' . $coop_name, 'cooperative_id' => $coop_id];
            event(new AuditTrailEvent($activity_log));
            DB::commit();
            toastr()->success('County govt official deleted Successfully');
            return redirect()->route('admin.county-govt-officials.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('County govt official failed to delete');
            return redirect()->route('admin.county-govt-officials.show');
        }
    }
}
