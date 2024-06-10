<?php

namespace App\Imports\CooperativeAdmin;

use App\Bank;
use App\BankBranch;
use App\Country;
use App\County;
use App\Events\NewUserRegisteredEvent;
use App\Exceptions\UnableToCreateEmployeeException;
use App\FarmerCooperative;
use App\Http\Traits\Farmer;
use App\Location;
use App\Product;
use App\Route;
use App\Rules\BankBranchRule;
use App\Rules\BirthYearRule;
use App\Rules\ProductRule;
use App\SubCounty;
use App\User;
use Auth;
use DB;
use Hash;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Spatie\Permission\Models\Role;


HeadingRowFormatter::extend('custom', function ($value, $key) {
    return str_replace(" ", "_", $value);
});

class FarmerImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Farmer;

    /**
     * @throws UnableToCreateEmployeeException
     * @throws \Throwable
     */
    public function collection(Collection $rows)
    {
        $user = Auth::user();
        $authUser = Auth::user();
        $authUserId = $authUser->id;
        $coop_id = $authUser->cooperative_id;


        DB::beginTransaction();

        foreach ($rows as $row) {
            // create user
            $user = new User();
            $user->first_name = ucwords(strtolower($row["first_name"]));
            $user->other_names = ucwords(strtolower($row["other_names"]));
            $user->cooperative_id = $row["cooperative_id"];
            $user->email = $row["email"];
            $user->username = $row["username"];
            $password = generate_password();
            $user->password = Hash::make($password);
            $user->save();

            // farmer
            $farmer = new Farmer();
            $farmer->user_id = $user->id;
            $farmer->country_id = Country::where("name", $row["country"])->firstOrFail()->id;
            $farmer->county_id = County::where("name", $row["county"])->firstOrFail()->id;
            $farmer->sub_county_id = SubCounty::where("name", $row["sub_county"])->firstOrFail()->id;
            $farmer->id_no = $row["id_no"];
            $farmer->member_no = $row["id_no"];
            $farmer->gender = $row["gender"];
            $farmer->phone_no = $row["phone_number"];
            $farmer->kra = $row["kra"];
            $farmer->save();

            // farmer cooperative
            $farmerCoop = new FarmerCooperative();
            $farmerCoop->farmer_id = $farmer->id;
            $farmerCoop->cooperative_id = $coop_id;
            $farmerCoop->save();

            $data = [
                "name" => ucwords(strtolower($row['first_name']))
                    . ' ' . ucwords(strtolower($row['other_names'])),
                "email" => $row['email'],
                "password" => $password
            ];

            event(new NewUserRegisteredEvent($data));
        }

        DB::commit();
    }

    public function rules(): array
    {
        $user = Auth::user();
        return [
            'first_name' => ['required'],
            'other_names' => ['required'],
            'username' => ['required', 'unique:' . User::class . ',username'],
            'email' => ['required', 'unique:' . User::class . ',email'],
            'country' => ['required', Rule::in(Country::select('name')->pluck('name')->toArray())],
            'county' => ['required', Rule::in(County::select('name')->pluck('name')->toArray())],
            'sub_county' => ['required', Rule::in(SubCounty::select('name')->pluck('name')->toArray())],
            'kra' => 'string|unique:farmers,kra',
            'id_no' => 'required|unique:farmers,id_no',
            'dob' => ['required', 'date', 'date_format:Y-m-d', new BirthYearRule()],
            'gender' => Rule::in(config('enums.employee_configs')['gender']),
            'phone_number' => ['required', 'regex:/^[0-9]{10}$/', 'unique:farmers,phone_no'],
        ];
    }
}
