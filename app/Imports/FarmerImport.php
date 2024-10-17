<?php

namespace App\Imports;

use App\Bank;
use App\BankBranch;
use App\Country;
use App\Events\NewUserRegisteredEvent;
use App\Exceptions\UnableToCreateEmployeeException;
use App\Http\Traits\Farmer;
use App\Location;
use App\Product;
use App\Route;
use App\Rules\BankBranchRule;
use App\Rules\BirthYearRule;
use App\Rules\ProductRule;
use App\User;
use Auth;
use DB;
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

        foreach ($rows as $row) {
            $country_id = Country::where('name', $row['country'])->first()->id;
            $bank_id = Bank::select('id')->where('cooperative_id', $user->cooperative_id)
                ->where('name', $row['bank'])
                ->first()->id;
            $bank_branch_id = BankBranch::select('id')
                ->where('name', $row['bank_branch'])
                ->where('bank_id', $bank_id)
                ->where('cooperative_id', $user->cooperative_id)
                ->first()->id;
            $route_id = Route::select('id')->where('cooperative_id', $user->cooperative_id)
                ->where('name', $row['route'])
                ->first()->id;

            $products = explode(',', $row['products']);
            $products_ids = Product::select('id')
                    ->where('cooperative_id', $user->cooperative_id)
                    ->whereIn('name', $products)
                    ->pluck('id')->toArray();
            $location_id = Location::select('id')
                ->where('name', $row['location'])
                ->where('cooperative_id', $user->cooperative_id)
                ->first()->id;
            $gender = substr(strtoupper($row['gender']), 0, 1);
            $r = (object)[
                'f_name' => $row['first_name'],
                'o_names' => $row['other_names'],
                'u_name' => $row['user_name'],
                'user_email' => $row['email'],
                'country_id' => $country_id,
                'county' => $row['county'],
                'location' => $location_id,
                'dob' => $row['dob'],
                'id_no' => $row['id_no'],
                'phone_no' => $row['phone_number'],
                'route_id' => $route_id,
                'bank_account' => $row['bank_acc_no'],
                'member_no' => $row['member_no'],
                'bank_branch_id' => $bank_branch_id,
                'customer_type' => config('enums.farmer_customer_types')[strtolower($row['customer_type'])],
                'kra' => $row['kra'],
                'farm_size' => $row['farm_size'],
                'gender' => in_array($gender, ['F', 'M']) ? $gender : 'X',
                'products' => $products_ids,
            ];


            $password = generate_password();
            $newUser = $this->saveUser($r, new User(), $password, $user->cooperative_id);
            $role = Role::select('id', 'name')->where('name', '=', 'farmer')->first();
            $newUser->assignRole($role->name);
            $this->saveFarmer($r, $newUser, new \App\Farmer(), $row['member_no']);

            $data = ["name" => ucwords(strtolower($row['first_name']))
                . ' ' . ucwords(strtolower($row['other_names'])),
                "email" => $row['email'],
                "password" => $password];

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
            'user_name' => ['required', 'unique:' . User::class . ',username'],
            'email' => ['required', 'unique:' . User::class . ',email'],
            'country' => Rule::in(Country::select('name')->pluck('name')->toArray()),
            'county' => ['required'],
            'kra' => ['required'],
            'location' => Rule::in(Location::select('name')->pluck('name')->toArray()),
            'id_no' => ['required', 'unique:' . \App\Farmer::class . ',id_no'],
            'dob' => ['required', 'date', 'date_format:Y-m-d', new BirthYearRule()],
            'gender' => Rule::in(config('enums.employee_configs')['gender']),
            'phone_number' => ['required', 'regex:/^[0-9]{10}$/', 'unique:' . \App\Farmer::class . ',phone_no'],
            'bank_acc_no' => ['required'],
            'bank' => Rule::in(Bank::select('name')->where('cooperative_id', $user->cooperative_id)
                ->pluck('name')->toArray()),
            'bank_branch' => ['exists:' . BankBranch::class . ',name', new BankBranchRule($user)],
            'member_no' => ['required', 'unique:' . \App\Farmer::class . ',member_no'],
            'customer_type' => Rule::in(array_map('ucwords', array_values(config('enums.farmer_customer_types')))),
            'products' => [new ProductRule($user)],
            'farm_size' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'route' => Rule::in(Route::select('name')
                ->where('cooperative_id', $user->cooperative_id)
                ->pluck('name')->toArray()),

        ];
    }
}
