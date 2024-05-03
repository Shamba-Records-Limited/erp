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
            $farmer->country_id = $req->country_id;
            $farmer->county = $req->county;
            $farmer->location_id = get_location_details($req->location, $user->cooperative_id);
            $farmer->id_no = $req->id_no;
            $farmer->phone_no = $req->phone_no;
            $farmer->route_id = $req->route_id;
            $farmer->bank_account = $req->bank_account;
            $farmer->member_no = $member_no;
            $farmer->bank_branch_id = $req->bank_branch_id;
            $farmer->customer_type = $req->customer_type;
            $farmer->kra = $req->kra;
            $farmer->user_id = $user->id;
            $farmer->age = $age;
            $farmer->dob = $dob;
            $farmer->gender = $req->gender;
            $farmer->farm_size = $req->farm_size;

            $products = $user->products()->pluck('product_id')->toArray();
            if (!empty($products)) {
                $new_products_to_add = array_values(array_diff($req->products, $products));
                $new_products_to_remove = array_values(array_diff($products, $req->products));

                if (!empty($new_products_to_add)) {
                    $user->products()->attach($new_products_to_add);
                }

                if (!empty($new_products_to_remove)) {
                    $user->products()->detach($new_products_to_remove);
                }

            } else {
                $user->products()->attach($req->products);
            }

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
        $user->cooperative_id = $cooperativeId;
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
