<?php

namespace App\Http\Controllers\API;

use App\Bank;
use App\BankBranch;
use App\Cooperative;
use App\Country;
use App\Events\AuditTrailEvent;
use App\Events\NewUserRegisteredEvent;
use App\Farmer;
use App\Http\Controllers\Controller;
use App\Product;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {

        $rules=array(
            "email" => "required|email",
            "password" => "required",
        );
        $messages=array(
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'password.required' => 'Password is required',
        );
        $validator=Validator::make($request->all(),$rules,$messages);

        if($validator->fails())
        {
            $messages=$validator->messages();
            return response()->json([
                "success"=>false,
                "message"=>"Bad request",
                "data"=>["errors"=>$messages->all()]
            ], 400);
        }

        try{

            $credentials = ['email'=> $request->email, 'password'=> $request->password];

            if(auth()->attempt($credentials))
            {
                $user = auth()->user();
                if($user->hasRole(['farmer', 'agent', 'cooperative admin', 'vet', 'accountant', 'employee', 'admin']))
                {
                    $token = $user->createToken('ERP')->accessToken;

                    $user_obj = [
                        "id"=>$user->id,
                        "name"=>ucwords(strtolower($user->first_name.' '.$user->other_names)),
                        "email" => $user->email,
                        "cooperative"=>["name"=>ucwords($user->cooperative->name), "id"=>$user->cooperative_id],
                        "role"=>$user->getRoleNames()[0]
                    ];

                    return response()->json([
                        "success"=>true,
                        "message"=>"Authenticated successfully",
                        "data"=>["token"=>$token, "user"=>$user_obj]
                    ]);
                }

                return response()->json([
                    "success"=>false,
                    "message"=>"You do not have the right role to access this app, contact admin!",
                    "data"=>null
                ], 401);

            }

            return response()->json([
                "success"=>false,
                "message"=>"Invalid username or password",
                "data"=>null
            ], 401);

        }catch (\Exception $ex)
        {
            Log::error($ex->getMessage());
            return response()->json([
                "success"=>false,
                "message"=>"Oops an error occurred",
                "data"=>null
            ], 500);
        }

    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        auth()->user()->token()->revoke();
        return response()->json([
            "success"=>true,
            "message"=>"Success",
            "data"=>null
        ]);
    }

    public function get_cooperatives(): \Illuminate\Http\JsonResponse
    {
        $cooperatives = Cooperative::select(["id","name"])->get();
        return response()->json([
            "success"=>true,
            "message"=>"Success",
            "data"=>$cooperatives
        ]);
    }

    public function get_banks($cooperative_id): \Illuminate\Http\JsonResponse
    {
        $banks = Bank::select(["id","name"])->where("cooperative_id", $cooperative_id)->get();
        return response()->json([
            "success"=>true,
            "message"=>"Success",
            "data"=>$banks
        ]);
    }

    public function get_bank_branches($bank_id): \Illuminate\Http\JsonResponse
    {
        $bank_branches = BankBranch::select(["id","name"])->where("bank_id", $bank_id)->get();
        return response()->json([
            "success"=>true,
            "message"=>"Success",
            "data"=>$bank_branches
        ]);
    }

    public function get_products($cooperative_id): \Illuminate\Http\JsonResponse
    {
        $products = Product::select(["id","name"])->where("cooperative_id", $cooperative_id)->get();
        return response()->json([
            "success"=>true,
            "message"=>"Success",
            "data"=>$products
        ]);
    }

    public function get_countries(): \Illuminate\Http\JsonResponse
    {
        $countries = Country::select(["id","name"])->get();
        return response()->json([
            "success"=>true,
            "message"=>"Success",
            "data"=>$countries
        ]);
    }



    public function farmer_register(Request $req): \Illuminate\Http\JsonResponse
    {
        $age = Carbon::parse($req->dob)->age;
        if($age < 18)
        {
            return response()->json([
                "success"=>false,
                "message"=>"DOB age calculation is below 18years",
                "data"=>null
            ], 400);
        }


        $rules=array(
            'country_id' => 'required|string',
            'cooperative_id' => 'required|string',
            'county' => 'required|string',
            'location' => 'required|string',
            'id_no' => 'required|string',
            'phone_no' => 'required|regex:/^[0-9]{10}$/|unique:cooperatives,contact_details',
            'route_id' => 'required|string',
            'bank_account' => 'required|string',
            'member_no' => 'required|string',
            'bank_branch_id' => 'required|string',
            'customer_type' => 'required|string',
            'kra' => 'required|string',
            'f_name' => 'required|string',
            'o_names' => 'required|string',
            'user_email' => 'required|email|unique:users,email',
            'u_name' => 'required|unique:users,username',
            'products' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'farm_size' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        );
        $messages=array(
            'country_id.required' => 'Country is required',
            'country_id.string' => 'Country format is invalid only string required',
            'cooperative_id.required' => 'Cooperative is required',
            'cooperative_id.string' => 'Cooperative format is invalid only string required',
            'county.required' => 'County is required',
            'county.string' => 'County format is invalid only string required',
            'location.required' => 'Location is required',
            'location.string' => 'Location format is invalid only string required',
            'id_no.required' => 'ID number is required',
            'id_no.string' => 'ID number format is invalid only string required',
            'phone_no.required' => 'Phone number is required',
            'phone_no.regex' => 'Phone number format is invalid',
            'route_id.required' => 'Route is required',
            'route_id.string' => 'Route format is invalid only string required',
            'bank_account.required' => 'Bank account is required',
            'bank_account.string' => 'Bank account is invalid only string required',
            'member_no.required' => 'Member number is required',
            'member_no.string' => 'Member number is invalid only string required',
            'bank_branch_id.required' => 'Bank branch is required',
            'bank_branch_id.string' => 'Bank branch is invalid only string required',
            'customer_type.required' => 'Customer type is required',
            'customer_type.string' => 'Customer type is invalid only string required',
            'kra.required' => 'KRA is required',
            'kra.string' => 'KRA is invalid only string required',
            'f_name.required' => 'First name is required',
            'f_name.string' => 'First name is invalid only string required',
            'o_names.required' => 'Other names is required',
            'o_names.string' => 'Other names is invalid only string required',
            'user_email.required' => 'Email is required',
            'user_email.email' => 'Email is invalid only string required',
            'user_email.unique' => 'Email has already been taken',
            'u_name.required' => 'Username is required',
            'u_name.unique' => 'Username has already been taken',
            'products.required' => 'Products are required',
            'dob.required' => 'Date of birth is required',
            'gender.required' => 'Gender is required',
            'farm_size.required' => 'Farm size is required',
            'farm_size.regex' => 'Farm size can only be in number',
        );
        $validator=Validator::make($req->all(),$rules,$messages);

        if($validator->fails())
        {
            $messages=$validator->messages();
            return response()->json([
                "success"=>false,
                "message"=>"Bad request",
                "data"=>["errors"=>$messages->all()]
            ], 400);
        }


        try {
            DB::beginTransaction();
            //generate password
            $password = generate_password();

            //new user and farmer objecr
            $user = new User();
            $farmer = new Farmer();
            //save user and farmer
            $this->persist_user($req, $user,$password);
            $new_user = User::where('email','=',$req->user_email)->first();
            $this->persist($req,$new_user,$farmer);
            //assign role to user
            $role = Role::select('id','name')->where('name','=','farmer')->first();
            $new_user->assignRole($role->name);

            //audit trail log
            $role_created_audit = ['user_id' => $new_user->id, 'activity' => 'API Assigned '. $role->name.
                ' to  '.$new_user->username, 'cooperative_id'=> $req->cooperative_id];
            event(new AuditTrailEvent($role_created_audit));

            //send email and new audit trail
            $data = ["name" => ucwords( strtolower($req->f_name)).' '.ucwords(strtolower($req->o_names)),
                "email" => $req->user_email, "password" =>$password ];
            $audit_trail_data = ['user_id' => $new_user->id, 'activity' => 'API Created '.$new_user->username.'account', 'cooperative_id'=> $req->cooperative_id];
            event(new AuditTrailEvent($audit_trail_data));
            event(new NewUserRegisteredEvent($data));
            DB::commit();
            return response()->json([
                "success"=>true,
                "message"=>"Success",
                "data"=>null
            ]);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            return response()->json([
                "success"=>false,
                "message"=>$th->getMessage(),
                "data"=>null
            ], 400);
        }

    }

    private function persist($req, $user, $farmer)
    {
        $dob = Carbon::parse( $req->dob)->format('Y-m-d');
        $age = Carbon::parse($req->dob)->age;
        $farmer->country_id = $req->country_id;
        $farmer->county = $req->county;
        $farmer->location = $req->location;
        $farmer->id_no = $req->id_no;
        $farmer->phone_no = $req->phone_no;
        $farmer->route_id = $req->route_id;
        $farmer->bank_account = $req->bank_account;
        $farmer->member_no = $req->member_no;
        $farmer->bank_branch_id = $req->bank_branch_id;
        $farmer->customer_type = $req->customer_type;
        $farmer->kra = $req->kra;
        $farmer->user_id = $user->id;
        $farmer->age = $age;
        $farmer->dob = $dob;
        $farmer->gender = $req->gender;
        $farmer->farm_size = $req->farm_size;
        $user->products()->attach($req->products);
        $farmer->save();
    }


    private function persist_user($request, $user,  $password)
    {
        $user->first_name = ucwords(strtolower($request->f_name));
        $user->other_names =ucwords(strtolower($request->o_names));
        $user->cooperative_id = $request->cooperative_id;
        $user->email =$request->user_email;
        $user->username =$request->u_name;
        $user->password = Hash::make($password);
        $user->save();
    }
}
