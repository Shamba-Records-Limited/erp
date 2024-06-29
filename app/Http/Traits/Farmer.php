<?php

namespace App\Http\Traits;

use App\User;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Log;

trait Farmer
{

    /**
     * @param $req
     * @param User $user
     * @param \App\Farmer $farmer
     * @param String $member_no
     * @return void
     * @throws \Exception
     */
    public function saveFarmer($req, $user, $farmer, $member_no)
    {
        Log::info("Saving Farmer Profile");
        try {
            $dob = Carbon::parse($req->dob)->format('Y-m-d');
            $age = Carbon::parse($req->dob)->age;
            $farmer->country_code = $req->country_code;
            $farmer->county_id = $req->county_id;
            $farmer->sub_county_id = $req->sub_county_id;
            $farmer->id_no = $req->id_no;
            $farmer->phone_no = $req->phone_no;
            $farmer->member_no = $member_no;
            $farmer->user_id = $user->id;
            $farmer->age = $age;
            $farmer->dob = $dob;
            $farmer->gender = $req->gender[0];

            $farmer->save();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
        }
    }

    /**
     * @param $request
     * @param User $user
     * @param String $password
     * @param string $cooperativeId
     * @return User
     */
    public static function saveUser($request, User $user, string $password, string $cooperativeId): User
    {
        $user->first_name = ucwords(strtolower($request->f_name));
        $user->other_names = ucwords(strtolower($request->o_names));
        // $user->cooperative_id = $cooperativeId;
        $user->email = $request->user_email;
        $user->username = $request->u_name;
        $user->password = Hash::make($password);
        save_user_image($user, $request);
        $user->save();
        return $user;
    }


    private function update_profile(User $user, $request)
    {
        Log::info("Updating user profile");
        $user->first_name = ucwords(strtolower($request->f_name));
        $user->other_names = ucwords(strtolower($request->o_names));
        $user->email = $request->user_email;
        $user->username = $request->u_name;
        save_user_image($user, $request);
        $user->save();
        $this->saveFarmer($request, $user, $user->farmer, $request->member_no);
    }


}
