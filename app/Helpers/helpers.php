<?php

use App\Account;
use App\AccountingRule;
use App\Collection;
use App\Events\AuditTrailEvent;
use App\IncomeAndExpense;
use App\InsuranceTransactionHistory;
use App\InvoicePayment;
use App\LoanInstallment;
use App\Production;
use App\ReturnedItem;
use App\User;
use App\VetBooking;
use App\Sale;
use App\SaleItem;
use App\Farmer;
use App\Loan;
use App\LoanRepayment;
use App\WalletTransaction;
use App\Wallet;
use App\CooperativeFinancialPeriod;
use App\CooperativeProperty;
use App\CooperativeWallet;
use App\Location;
use App\NewInvoice;
use App\Receipt;
use App\ReceiptItem;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Mockery\Exception;
use Webpatser\Uuid\Uuid;

const COLORS = [
    "#5D62B4",
    "#54C3BE",
    "#EF726F",
    "#F9C446",
    "#f20812",
    "#0f412a",
    "#1a0b81",
    "#bcbc08",
    "#6d3c2c",
    "#2ac48c",
    "#6b5557",
    "#de6329"
];

const MINIMUM_TAXABLE_AMOUNT = 24000;
const MINIMUM_TAXABLE_AMOUNT_RATE = 0.1;
const TAXABLE_BAND_1_NEXT_8333 = 8333;
const TAXABLE_BAND_1_NEXT_8333_RATE = 0.25;
const TAXABLE_BAND_2_NEXT_467667 = 467667;
const TAXABLE_BAND_2_NEXT_467667_RATE = 0.30;
const TAXABLE_BAND_3_NEXT_300000 = 300000;
const TAXABLE_BAND_3_NEXT_300000_RATE = 0.325;
const TAXABLE_BAND_4_ABOVE_800000_RATE = 0.35;
const NSSF_TIER_1_LOWEST_LIMIT = 7000;
const NSSF_TIER_2_UPPER_LIMIT = 29000;
const NSSF_TIER_1_AND_TIER_2_RATE = 0.06;

if (!function_exists('check_if_booked')) {
    function check_if_booked($start_time, $end_time, $vet, $bookingId = null)
    {
        if ($bookingId != null) {
            $vet_booking = VetBooking::find($bookingId);
            if ($vet_booking->event_start == $start_time && $vet_booking->event_end == $end_time) {
                return 0;
            }

            return VetBooking::where('vet_id', $vet)
                ->where('farmer_id', '!=', $vet_booking->farmer_id)
                ->whereBetween('event_start', [$start_time, $end_time])
                ->orWhereBetween('event_end', [$start_time, $end_time])
                ->count() - 1;
        }

        return VetBooking::where('vet_id', $vet)
            ->whereBetween('event_start', [$start_time, $end_time])
            ->orWhereBetween('event_end', [$start_time, $end_time])
            ->count();
    }
}


// For add'active' class for activated route nav-item
if (!function_exists('active_class')) {
    function active_class($path, $active = 'active')
    {
        return call_user_func_array('Request::is', (array)$path) ? $active : '';
    }
}


if (!function_exists('is_active_route')) {
    // For checking activated route
    function is_active_route($path)
    {
        return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
    }
}

// For add 'show' class for activated route collapse
if (!function_exists('show_class')) {
    function show_class($path)
    {
        return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
    }
}


if (!function_exists('get_countries')) {
    function get_countries()
    {
        // load countries from json file: countries.json {'<country_code': {'name': '<name>', 'flag': '<flag>', 'dial_code': '<dial_code>'}}
        $raw_countries = json_decode(file_get_contents(base_path('countries.json')), true);
        $countries = [];

        foreach ($raw_countries as $code => $value) {
            # code...
            $countries[] = [
                'code' => $code,
                'name' => $value['name'],
                'flag' => $value['flag'],
                'dial_code' => $value['dial_code']
            ];
        }

        return $countries;
    }
}


if (!function_exists('get_country_flag')) {
    function get_country_flag($iso)
    {
        return 'files/cooperative/country-flags/' . $iso . '.png';
    }
}


if (!function_exists('generate_password')) {
    function generate_password()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = substr(str_shuffle($permitted_chars), 0, 8);
        // todo: remove after go live
        $password = "12345678";
        return $password;
    }
}

if (!function_exists('store_image')) {
    function store_image($request, $file, $request_file, $path, $height, $width)
    {
        Log::info("Saving image to storage");
        $name = null;
        if ($request->has($file)) {
            $name = $request_file->store($path, 'public');
            // dd(public_path('storage/' . $name));
            // $resized_image = Image::make(public_path('storage/' . $name))->fit($height, $width);
            // $resized_image->save();
        }

        Log::info("Image {$name} saved");

        return $name;
    }
}

if (!function_exists('save_user_image')) {
    function save_user_image(User $user, $request)
    {
        Log::info("Updating user profile picture");
        if ($user->profile_picture && File::exists('storage/' . $user->profile_picture)) {
            $file_path = 'storage/' . $user->profile_picture;
            Log::info("Deleting file: {$file_path}");
            File::delete($file_path);
        }

        $user->profile_picture = $request->has('profile_picture') ?
            store_image(
                $request,
                "profile_picture",
                $request->file("profile_picture"),
                "images/profile",
                200,
                200
            ) : $user->profile_picture;
    }
}

if (!function_exists('calculate_percentage_change')) {
    function calculate_percentage_change($current, $previous)
    {
        return (($current - $previous) / ($previous)) * 100;
    }
}

if (!function_exists('round_to_the_nearest_anything')) {
    function round_to_the_nearest_anything($value, $round_to): int
    {
        $mod = $value % $round_to;
        return $value + ($mod < ($round_to / 2) ? -$mod : $round_to - $mod);
    }
}

if (!function_exists('get_id_of_user_with_role')) {
    function get_id_of_user_with_role($role, $cooperative): array
    {
        return (array)User::select('id')->with(['roles'])
            ->whereHas('roles', function ($q) use ($role) {
                $q->where('name', '=', $role);
            })->where('cooperative_id', $cooperative)->pluck('id')->toArray();
    }
}

if (!function_exists('get_farmers')) {
    function get_farmers($userIds, $cooperative_id): object
    {
        return \App\Farmer::select(['farmers.id as id'])->join('users', 'users.id', '=', 'farmers.user_id')
            ->where('users.cooperative_id', $cooperative_id)->whereIn('farmers.user_id', $userIds);
    }
}

if (!function_exists('get_statement_data')) {
    function get_statement_data($coop): array
    {
        // get period
        $period = CooperativeFinancialPeriod::where('cooperative_id', $coop)->first();

        $farmers = User::where('cooperative_id', $coop)->with('farmers')->whereHas('farmer')->pluck('id');
        $farmer_ids = Farmer::whereIn('user_id', $farmers)->pluck('id');
        //sales
        $sales_ids = Sale::where('cooperative_id', $coop)->pluck('id');
        $sales_amount = SaleItem::whereIn('sales_id', $sales_ids)->sum('amount');
        $sales_discount = SaleItem::whereIn('sales_id', $sales_ids)->sum('discount');
        $sales = $sales_amount - $sales_discount;
        //loans
        $loan_ids = Loan::whereIn('farmer_id', $farmer_ids)->pluck('id');
        $loaned_out = Loan::whereIn('farmer_id', $farmer_ids)->sum('amount');
        $loan_repayments = LoanRepayment::whereIn('loan_id', $loan_ids)->whereHas('wallet_transaction')->with('wallet_transaction')->get()->sum('wallet_transaction.amount');

        //withdrawals and savings
        $farmer_wallet_ids = Wallet::whereIn('farmer_id', $farmer_ids)->pluck('id');
        $farmer_withdrawals = WalletTransaction::whereIn('wallet_id', $farmer_wallet_ids)->where('type', 'LIKE', '%withdrawal%')->sum('amount');
        $farmer_savings = WalletTransaction::whereIn('wallet_id', $farmer_wallet_ids)->where('type', 'saving')->orWhere('type', 'savings')->sum('amount');

        //purchases
        $purchases = CooperativeProperty::where('cooperative_id', $coop)->sum('buying_price');
        $property = CooperativeProperty::where('cooperative_id', $coop)->sum('value');
        //wallet
        $cash = CooperativeWallet::where('cooperative_id', $coop)->sum('balance');
        $inflow = [
            'sales' => $sales,
            'loan_repayments' => $loan_repayments,
            'farmer_savings' => $farmer_savings,
            'property' => $property,
            'cash' => $cash
        ];
        $outflow = [
            'loan' => $loaned_out,
            'sales_discount' => $sales_discount,
            'farmer_withdrawals' => $farmer_withdrawals,
            'purchases' => $purchases,
        ];
        $data = [
            'period' => $period,
            'total_inflows' => $sales + $loan_repayments + $farmer_savings + $property,
            'total_outflows' => $loaned_out + $sales_discount + $farmer_withdrawals + $purchases,
            'inflow' => $inflow,
            'outflow' => $outflow
        ];
        return $data;
    }
}


if (!function_exists('create_account_transaction_old')) {
    function create_account_transaction_old($acc_ledger, $amount, $description, $coop_id = null, $side = null): bool
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            //check if we have active financial period
            $today = date('Y-m-d');
            $active_financial_period = CooperativeFinancialPeriod::whereDate('start_period', '<=', $today)
                ->whereDate('end_period', '>=', $today)->count();

            if ($active_financial_period > 0) {
                $accounting_ledger = \App\AccountingLedger::where('name', $acc_ledger)->first();
                $accounting_ledger_id = $accounting_ledger->id;

                $is_credit = $side == 'credit' ? true : false; // $accounting_ledger->parent_ledger->id == 1;

                if ($amount < 0) {
                    $is_credit = !$is_credit;
                    $amount = -1 * $amount;
                }

                if ($accounting_ledger_id) {
                    $acc_trx = new \App\AccountingTransaction();
                    $acc_trx->accounting_ledger_id = $accounting_ledger_id;
                    $acc_trx->date = date('Y-m-d');
                    if ($is_credit) {
                        $acc_trx->credit = $amount;
                    } else {
                        $acc_trx->debit = $amount;
                    }
                    if ($coop_id == null) {
                        $cooperative_id = \Illuminate\Support\Facades\Auth::user()->cooperative_id;
                    } else {
                        $cooperative_id = $coop_id;
                    }
                    $acc_trx->particulars = $description;
                    $acc_trx->created_at = \Carbon\Carbon::now();
                    $acc_trx->cooperative_id = $cooperative_id;

                    $acc_trx->save();
                    \Illuminate\Support\Facades\DB::commit();
                    return true;
                }

                return false;
            } else {
                \Illuminate\Support\Facades\Log::error('No active financial period existing');
                return false;
            }
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
            \Illuminate\Support\Facades\DB::rollBack();
            return false;
        }
    }
}

if (!function_exists('create_account_transaction')) {
    function create_account_transaction($accounting_rule, $amount, $description, $coop_id = null): bool
    {
        try {

            DB::beginTransaction();

            $coop_id = !is_null($coop_id) ? $coop_id : Auth::user()->cooperative_id;

            //check if we have active financial period
            $today = date('Y-m-d');
            $active_financial_period = CooperativeFinancialPeriod::whereDate('start_period', '<=', $today)
                ->whereDate('end_period', '>=', $today)->count();

            // get accounting rule
            $rule = AccountingRule::where('name', $accounting_rule)->where('cooperative_id', $coop_id)->first();

            if ($active_financial_period > 0) {
                Log::info("We have active financial period");
                if ($rule) {
                    Log::info("We have Accounting Rule");
                    $date = date('Y-m-d');

                    // credit leg
                    $credit = new \App\AccountingTransaction();
                    $credit->accounting_ledger_id = $rule->credit_ledger_id;
                    $credit->date = $date;
                    $credit->credit = $amount;
                    $credit->particulars = $description;
                    $credit->created_at = \Carbon\Carbon::now();
                    $credit->cooperative_id = $coop_id;
                    $credit->save();

                    // debit leg
                    $debit = new \App\AccountingTransaction();
                    $debit->accounting_ledger_id = $rule->debit_ledger_id;
                    $debit->date = $date;
                    $debit->debit = $amount;
                    $debit->particulars = $description;
                    $debit->created_at = \Carbon\Carbon::now();
                    $debit->cooperative_id = $coop_id;
                    $debit->save();

                    DB::commit();

                    return true;
                }

                Log::info("No accounting Rule");

                return false;
            } else {
                Log::error('No active financial period existing');
                return false;
            }
        } catch (Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            DB::rollBack();
            return false;
        }
    }
}

if (!function_exists('get_financial_period_statement_ranges')) {

    function get_financial_period_statement_ranges($financial_period, $from = null, $to = null): array
    {
        $fy = CooperativeFinancialPeriod::find($financial_period);

        if ($from == null) {
            $from = $fy->start_period;
        }

        if ($to == null) {
            $to = $fy->end_period;
        }

        $period = Carbon::parse($from)->format('M d, Y') . ' / ' . Carbon::parse($to)
            ->format('M d, Y');

        return [
            "period" => $period,
            "balance_bf" => $fy->balance_bf,
            "balance_cf" => $fy->balance_cf,
            "filter_data" => [
                "from" => Carbon::parse($from)->format('Y-m-d'),
                "to" => Carbon::parse($to)->format('Y-m-d')
            ]

        ];
    }
}


if (!function_exists('convert_month_to_name')) {

    function convert_month_to_name($month): string
    {
        switch ($month) {
            case 1:
                return "January";
            case 2:
                return "February";
            case 3:
                return "March";
            case 4:
                return "April";
            case 5:
                return "May";
            case 6:
                return "June";
            case 7:
                return "July";
            case 8:
                return "August";
            case 9:
                return "September";
            case 10:
                return "October";
            case 11:
                return "November";
            default:
                return "December";
        }
    }
}


if (!function_exists('has_recorded_income_expense')) {

    function has_recorded_income_expense($data): bool
    {
        try {

            DB::beginTransaction();
            $income_expense = new IncomeAndExpense();
            $income_expense->date = $data["date"] ?? Carbon::now()->format('Y-m-d');
            $income_expense->income = $data["income"];
            $income_expense->expense = $data["expense"];
            $income_expense->particulars = $data["particulars"];
            $income_expense->user_id = $data["user_id"];
            $income_expense->cooperative_id = $data["cooperative_id"];
            $income_expense->save();
            DB::commit();
            $data = ['user_id' => $data["user_id"], 'activity' => 'Added cooperative income/expense', 'cooperative_id' => $data['cooperative_id']];
            event(new AuditTrailEvent($data));
            return true;
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return false;
        }
    }
}

if (!function_exists('check_active_financial_period')) {

    function check_active_financial_period(): bool
    {
        $cooperative = Auth::user()->cooperative->id;
        $today = Carbon::now()->format('Y-m-d');
        return CooperativeFinancialPeriod::where('cooperative_id', $cooperative)
            ->whereDate('start_period', '<=', $today)
            ->whereDate('end_period', '>=', $today)
            ->where('active', true)
            ->count() === 3;
    }
}

if (!function_exists('can_view_module')) {

    function can_view_module($module_name): bool
    {
        $user = Auth::user();
        //$user = User::find('8bd53ce0-1f56-4e13-a225-3e4669cefb73');
        $cooperative = $user->cooperative->id;
        $roles = $user->cooperative_roles;
        if ($user->hasRole('super-admin')) {
            return true;
        } else if ($user->hasRole('cooperative admin')) {
            return true;
        } else
            if ($user->hasRole('employee')) {
            //get internal roles of the user.
            $user_roles = [];
            foreach ($roles as $r) {
                if (!in_array($r->id, $user_roles)) {
                    $user_roles[] = $r->id;
                }
            }
            //check if the module has that role
            $module = \App\SystemModule::where('name', $module_name)->first();

            if ($module) {
                $module_role_details = $module->cooperative_roles;
                foreach ($module_role_details as $m_role) {
                    if ($cooperative == $m_role->cooperative_id && in_array($m_role->id, $user_roles)) {
                        return true;
                    }
                }
                return false;
            }
            return false;
        }
        return false;
    }
}

if (!function_exists('has_right_permission')) {

    function has_right_permission($submodule, $operation): bool
    {
        $user = Auth::user();
        $cooperative = $user->cooperative->id;
        if ($user->hasRole('cooperative admin')) {
            return true;
        } else
            if ($user->hasRole('employee')) {

            $subModule = \App\SystemSubmodule::where('name', $submodule)->first();

            if ($subModule == null) {
                toastr()->error('Submodule: ' . $submodule . ' is not created. Contact admin for support');
                return false;
            }
            $permission = \App\InternalUserPermission::select('internal_user_permissions.' . $operation)
                ->where('employee_id', $user->id)
                ->where('submodule_id', $subModule->id)
                ->where('internal_user_permissions.' . $operation, 1)
                ->first();

            if ($permission) {
                return true;
            }

            $module_roles = $subModule->module->cooperative_roles->pluck('id')->toArray();
            $user_roles = $user->cooperative_roles->pluck('id')->toArray();
            $module_user_role = array_values(array_intersect($user_roles, $module_roles));

            if (empty($module_user_role)) {
                return false;
            }

            $rolePermission = \App\InternalRolePermission::select('internal_role_permissions.' . $operation)
                ->where('internal_role_permissions.' . $operation, 1)
                ->whereIn('internal_role_id', $module_user_role)
                ->get();

            if (count($rolePermission) > 0) {
                return true;
            }
            return false;
        }
        return false;
    }
}


if (!function_exists('get_cooperative_farmers')) {

    function get_cooperative_farmers($cooperative): \Illuminate\Support\Collection
    {

        return User::select("users.first_name", 'users.other_names', 'users.id')->where('users.cooperative_id', $cooperative)
            ->join("model_has_roles", "model_has_roles.model_id", "users.id")
            ->join("roles", "roles.id", "model_has_roles.role_id")
            ->where("roles.name", "farmer")
            ->with(['farmer', 'products'])
            ->orderBy('users.first_name')
            ->orderBy('users.other_names')
            ->orderBy('users.created_at')
            ->get();
    }
}

if (!function_exists('get_agents')) {

    function get_agents($cooperative): \Illuminate\Support\Collection
    {

        return User::select("users.first_name", 'users.other_names', 'users.id')
            ->where('users.cooperative_id', $cooperative)
            ->join("model_has_roles", "model_has_roles.model_id", "users.id")
            ->join("roles", "roles.id", "model_has_roles.role_id")
            ->where("roles.name", "agent")
            ->orderBy('users.first_name')
            ->get();
    }
}

if (!function_exists('get_random_number')) {

    function get_random_number($prev, $min, $max): int
    {
        $num = mt_rand($min, $max);
        if ($num === $prev) {
            return get_random_number($prev, $min, $max);
        }
        return $num;
    }
}

if (!function_exists('get_location_details')) {

    function get_location_details($id, $cooperativeId = null)
    {
        $cooId = $cooperativeId == null ? Auth::user()->cooperative_id : $cooperativeId;
        if (preg_match('/^place_/i', $id)) {

            $place_id = str_replace('place_', '', $id);

            $url = env('MAPS_API') . '/place/details/json?' . http_build_query([
                'fields' => 'name,geometry,place_id',
                'place_id' => $place_id,
                'key' => env('MAPS_API_KEY'),
            ]);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $resp = curl_exec($ch);

            $response = json_decode($resp, true);

            if ($response['status'] == 'OK') {

                $result = $response['result'];
                $location_id = (string)Uuid::generate(4);
                DB::insert('INSERT INTO locations (id, place_id, cooperative_id, name, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)', [
                    $location_id,
                    $result['place_id'],
                    $cooId,
                    $result['name'],
                    $result['geometry']['location']['lat'],
                    $result['geometry']['location']['lng']
                ]);

                return $location_id;
            }

            throw new Exception("An error occurred while fetching place $place_id from Maps");
        }

        return $id;
    }
}

if (!function_exists('volume_indicators')) {
    function volume_indicators(): array
    {
        return ["1" => "season", "2" => "acre", "3" => "livestock/poultry breed", "4" => "tree"];
    }
}

if (!function_exists('calculate_distance_maps_api')) {
    function calculate_distance_maps_api($origin, $destination)
    {
        $url = env('MAPS_API') . '/distancematrix/json?' . http_build_query([
            'origins' => 'place_id:' . $origin,
            'destinations' => 'place_id:' . $destination,
            'units' => 'metrics',
            'key' => env('MAPS_API_KEY'),
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);

        $response = json_decode($resp, true);

        if ($response['status'] === 'OK') {
            return (float)explode(' ', $response['rows'][0]['elements'][0]['distance']['text'])[0];
        }

        return 0;
    }
}

if (!function_exists('location_search_maps_api')) {
    function location_search_maps_api($query): \Illuminate\Http\JsonResponse
    {
        $locations = [];
        $url = env('MAPS_API') . '/place/queryautocomplete/json?' . http_build_query([
            'input' => $query,
            'location' => env('MAPS_LOCATION'),
            'radius' => env('MAPS_LOCATION_RADIUS'),
            'key' => env('MAPS_API_KEY'),
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);

        $response = json_decode($resp, true);

        if ($response['status'] == 'OK') {

            foreach ($response['predictions'] as $prediction) {

                if (array_key_exists('place_id', $prediction)) {
                    $locations[] = [
                        'id' => 'place_' . $prediction['place_id'],
                        'text' => $prediction['description'],
                    ];
                }
            }
        }

        return response()->json(compact('locations'));
    }
}

if (!function_exists('download_pdf')) {
    function download_pdf($columns, $data)
    {
        $pdf_view = $data['pdf_view'];
        $title = $data['title'];
        $image = $data['image'] ?? 'default_value';
        $period = Carbon::now()->format('D, d M Y  H:i:s');
        $records = $data['records'];
        $summation = $data['summation'] ?? null;
        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('letter', $data['orientation']);
        $pdf->loadView('pdfs.reports.general', compact('title', 'period', 'records', 'columns', 'summation','image'));
        $file_name = $data['filename'];
        return $pdf->download($file_name . '.pdf');
    }
}

if (!function_exists('update_wallet')) {
    /**
     * @param $request
     * @param $withdraw
     * @param $amount
     * @return false|mixed|string
     * @throws Throwable
     */
    function update_wallet($request, $withdraw, $amount, $farmer_id)
    {
        try {
            DB::beginTransaction();
            //get product price
            $farmer_wallet = Wallet::where('farmer_id', $farmer_id)->first();
            if ($farmer_wallet) {
                //update current balance
                if ($withdraw) {
                    $farmer_wallet->current_balance += ($amount);
                } else {
                    $farmer_wallet->current_balance -= ($request->amount);
                }

                $farmer_wallet->save();
            } else {
                return false;
            }

            $wallet = Wallet::where('farmer_id', $farmer_id)->first();

            $wallet_transaction = new WalletTransaction();
            $wallet_transaction->wallet_id = $wallet->id;
            $wallet_transaction->type = $withdraw ? 'saving withdrawal' : 'saving';
            $wallet_transaction->amount = $withdraw ? $amount : $request->amount;
            $wallet_transaction->reference = 'SWT' . date('Ymdhis');
            $wallet_transaction->source = $withdraw ? 'saving withdrawal' : 'saving';
            $wallet_transaction->initiator_id = Auth::user()->id;
            $wallet_transaction->description = $withdraw ? 'Withdraw from savings to wallet' : 'Saving Transaction';;
            $wallet_transaction->phone = null;
            $wallet_transaction->save();
            $created_wallet_trx_id = $wallet_transaction->fresh()->id;
            DB::commit();
            $message = $withdraw ? 'Wallet current balance incremented with ' . $amount . ' amount from saving'
                : 'Wallet current balance deducted with ' . $request->amount . ' amount to saving';
            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => $message, 'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            return $created_wallet_trx_id;
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);
            $message = $withdraw ? 'Failed to increment current balance with ' . $amount . ' amount from saving'
                : 'Failed to deduct current balance with ' . $request->amount . ' amount to saving';
            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => $message, 'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            return false;
        }
    }
}

if (!function_exists('loan_repayments_query')) {
    function loan_repayments_query(): string
    {
        $start_of_month = Carbon::now()->startOfMonth();
        $end_of_month = Carbon::now()->endOfMonth();
        $paid = LoanInstallment::STATUS_PAID;
        $patially_Paid = LoanInstallment::STATUS_PARTIALLY_PAID;
        return "SELECT u.first_name, u.other_names, f.phone_no,l.id, l.amount,l.amount AS principle,l.interest,li.amount AS installment, l.balance, ls.type, li.date as installment_date, l.due_date, li.status, li.source
                    FROM loan_installments li
                        INNER JOIN loans l ON li.loan_id = l.id
                        INNER JOIN loan_settings ls ON l.loan_setting_id = ls.id
                        INNER JOIN farmers f ON l.farmer_id = f.id
                        INNER JOIN users u ON f.user_id = u.id
                    WHERE li.date >= NOW() AND (li.status = '$paid' or li.status = '$patially_Paid') AND ( li.date BETWEEN  '$start_of_month' AND '$end_of_month') ORDER BY installment_date";
    }
}

if (!function_exists('loan_defaulters_query')) {
    function loan_defaulters_query(): string
    {
        $cooperative = Auth::user()->cooperative;
        $pending = LoanInstallment::STATUS_PENDING;
        $partially_paid = LoanInstallment::STATUS_PARTIALLY_PAID;
        return "SELECT li.id as installment_id, u.first_name, u.other_names, f.phone_no,l.id, l.amount,li.amount AS installment, l.balance, ls.type, li.date as due_date
                    FROM loan_installments li
                        INNER JOIN loans l ON li.loan_id = l.id
                        INNER JOIN loan_settings ls ON l.loan_setting_id = ls.id
                        INNER JOIN farmers f ON l.farmer_id = f.id
                        INNER JOIN users u ON f.user_id = u.id
                    WHERE li.date <= NOW() AND  (li.status = '$pending' OR li.status = '$$partially_paid') AND u.cooperative_id = '$cooperative->id' ORDER BY  l.id, li.status";
    }
}

if (!function_exists('record_wallet_transaction')) {
    function record_wallet_transaction($amount, $walletId, $type, $refPrefix, $description, $initiator)
    {
        $wallet_transaction = new WalletTransaction();
        $wallet_transaction->wallet_id = $walletId;
        $wallet_transaction->type = $type;
        $wallet_transaction->amount = $amount;
        $wallet_transaction->reference = $refPrefix . date('Ymdhis');
        $wallet_transaction->source = 'internal';
        $wallet_transaction->initiator_id = $initiator;
        $wallet_transaction->description = $description;
        $wallet_transaction->phone = null;
        $wallet_transaction->save();
    }
}

if (!function_exists('farmers')) {
    function farmers($cooperative): array
    {
        return DB::select("SELECT f.id as id, u.first_name, u.other_names FROM farmers f
                                    JOIN users u on f.user_id = u.id WHERE u.cooperative_id = '$cooperative'");
    }
}


if (!function_exists('record_insurance_transaction')) {
    /**
     * @param $subscriptionId
     * @param $amount
     * @param $type
     * @param $comments
     * @param User $user
     * @return void
     */
    function record_insurance_transaction($subscriptionId, $amount, $type, $comments, $user)
    {
        $trxn = new InsuranceTransactionHistory();
        $trxn->subscription_id = $subscriptionId;
        $trxn->amount = $amount;
        $trxn->type = $type;
        $trxn->comments = $comments;
        $trxn->cooperative_id = $user->cooperative_id;
        $trxn->created_by = $user->id;
        $trxn->save();
    }
}

if (!function_exists('generate_member_number')) {
    function generate_member_number(): string
    {
        $permitted_chars = '0123456789';
        return 'SH' . substr(str_shuffle($permitted_chars), 0, 4);
    }
}

if (!function_exists('tag_name_already_exists')) {
    function tag_name_already_exists($tag_name, $cooperative_id, $count): bool
    {
        return \App\Cow::where('cooperative_id', $cooperative_id)->where('tag_name', $tag_name)->count() > $count;
    }
}
if (!function_exists('default_wallet')) {
    function default_wallet($farmerId, $balance): Wallet
    {
        $wallet = new Wallet();
        $wallet->farmer_id = $farmerId;
        $wallet->available_balance = 0;
        $wallet->current_balance = $balance;
        $wallet->save();
        return $wallet->refresh();
    }
}
if (!function_exists('generateAccessToken')) {
    function generateAccessToken($consumer_key, $consumer_secret, $url)
    {
        $credentials = base64_encode($consumer_key . ":" . $consumer_secret);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Authorization: Basic " . $credentials]);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
        Log::debug("============================== Access Token =================================");
        $curl_response = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $access_token = json_decode($curl_response);
        Log::debug("Status Code: {$statusCode}");
        Log::debug("Raw Curl Response: {$curl_response}");
        return $access_token->access_token;
    }
}


if (!function_exists('record_sale_ledgers')) {
    function record_sale_ledgers($amount, User $user): bool
    {
        $data = [
            "date" => date('Y-m-d'),
            "income" => $amount,
            "expense" => null,
            "particulars" => "Sales",
            "user_id" => $user->id,
            "cooperative_id" => $user->cooperative_id,
        ];
        $record_expenditure = has_recorded_income_expense($data);

        return $record_expenditure;
    }
}

if (!function_exists('complete_sale_payment')) {
    function complete_sale_payment($invoicePaymentId): bool
    {
        $invoicePayment = InvoicePayment::findOrFail($invoicePaymentId);
        $invoice = $invoicePayment->invoice;
        $sale = $invoice->sale;
        $sale->balance -= $invoicePayment->amount;
        $mode = InvoicePayment::paymentModsDisplay[$invoicePayment->payment_platform];
        Log::info("Completing a sale id {$sale->id} via {$mode}");
        $description = "Sale payment of {$invoicePayment->amount} via {$mode}  for invoice id {$invoice->id}";

        $user = $invoicePayment->initiated_by;
        $expense_recorded = record_sale_ledgers($invoicePayment->amount, $user);

        // invoice payment
        $rule = $invoicePayment->payment_platform == 'cash' ? 'Credit Sales Cash Payments' : 'Credit Sales Bank Payments';
        $description = "Payment for invoice {$invoicePayment->id} via {$invoicePayment->payment_platform}";
        $ledgers_updated = create_account_transaction($rule, $invoicePayment->amount, $description);

        if ($expense_recorded && $ledgers_updated) {
            if ($invoice->status != \App\Invoice::STATUS_RETURNS_RECORDED) {
                if ($sale->balance == 0) {
                    $invoice->status = \App\Invoice::STATUS_PAID;
                } else {
                    $invoice->status = \App\Invoice::STATUS_PARTIAL_PAID;
                }
            }

            $invoice->updated_at = Carbon::now();
            $sale->paid_amount += $invoicePayment->amount;
            $sale->updated_at = Carbon::now();
            $invoicePayment->updated_at = Carbon::now();
            $invoicePayment->status = InvoicePayment::PAYMENT_STATUS_SUCCESS;
            $invoicePayment->save();
            $invoice->save();
            $sale->save();
            Log::info("Sale id {$sale->id} via {$mode} completed successfully");
        } else {
            Log::warning("Sale id {$sale->id} via {$mode} Failed");
        }
        $description = "Completed sale payment of {$invoicePayment->amount} via {$mode}  for invoice id {$invoice->id}";
        $audit_trail_data = [
            'user_id' => $user->id,
            'activity' => $description,
            'cooperative_id' => $user->cooperative_id
        ];
        event(new AuditTrailEvent($audit_trail_data));

        return $ledgers_updated;
    }
}


if (!function_exists('update_stock')) {
    function update_stock(Sale $sale): bool
    {
        foreach ($sale->saleItems as $sale_item) {
            if ($sale_item->collection_id) {
                Log::info("Updating a collection id {$sale_item->collection_id}");
                $produce_collected = Collection::with('product')->find($sale_item->collection_id);
                $all_products_quantity_in_collection = Collection::where("product_id", $produce_collected->product_id)
                    ->orderBy("available_quantity", "DESC")
                    ->get();
                $the_product = $produce_collected->product->name;
                $requested_quantity = $sale_item->quantity;
                $all_products_quantity_in_collection_sum = $all_products_quantity_in_collection->sum('available_quantity');
                if ($all_products_quantity_in_collection_sum >= $requested_quantity) {
                    $all_products_quantity_in_collection_arr = $all_products_quantity_in_collection->toArray();

                    $i = 0;
                    while ($requested_quantity > 0) {
                        $quantity_in_db = $all_products_quantity_in_collection_arr[$i]['available_quantity'];
                        $record_id = $all_products_quantity_in_collection_arr[$i]['id'];
                        if ($quantity_in_db > $requested_quantity) {
                            $collection_to_update = Collection::find($record_id);
                            $collection_to_update->available_quantity = $quantity_in_db - $requested_quantity;
                            $collection_to_update->save();
                            $requested_quantity = 0;
                        } else {
                            $collection_to_update = Collection::find($record_id);
                            $collection_to_update->available_quantity = 0;
                            $collection_to_update->save();
                            $requested_quantity -= $quantity_in_db;
                        }
                        $i++;
                    }
                } else {
                    Log::warning('Quantity available is not enough. Available: ' . $all_products_quantity_in_collection_sum . ' Sale id ' . $sale->id);
                    toastr()->error('Quantity available is not enough. Available: ' . $all_products_quantity_in_collection_sum);
                    return false;
                }
            } else if ($sale_item->manufactured_product_id) {
                $production = Production::find($sale_item->manufactured_product_id);
                if ($production['available_quantity'] >= $sale_item->quantity) {
                    $production->available_quantity -= $sale_item->quantity;
                    $production->save();
                } else {
                    Log::warning('Quantity available is not enough. Available: ' . $production['available_quantity'] . ' Sale id ' . $sale->id);
                    toastr()->error('Quantity available is not enough. Available: ' . $production['available_quantity']);
                    return false;
                }
            } else {

                Log::error("Could not determine if it is a manufactured product or  a collection sale for sale id: {$sale->id}");
                toastr()->error('Could not determine if it is a manufactured product or  a collection sale');
                return false;
            }
        }

        $audit_trail_data = ['user_id' => $sale->user->id, 'activity' => 'Stock updated successfully for sale id: ' . $sale->id, 'cooperative_id' => $sale->cooperative_id];
        event(new AuditTrailEvent($audit_trail_data));
        return true;
    }
}

if (!function_exists('split_dates')) {
    function split_dates($dates): array
    {
        $dates_array = explode(" - ", $dates);
        $from = Carbon::parse(trim($dates_array[0]))->format('Y-m-d');
        $to = Carbon::parse(trim($dates_array[1]))->format('Y-m-d');
        return [
            "from" => $from,
            "to" => $to
        ];
    }
}


if (!function_exists('sales_base_query')) {
    function sales_base_query($coop, $request, $type)
    {
        $voidSales = $request->void == 1;
        if ($voidSales) {
            $sales = Sale::onlyTrashed()->where('cooperative_id', $coop)->where('type', $type);
        } else {
            $sales = Sale::where('cooperative_id', $coop)->where('type', $type);
        }


        if ($request->customer) {
            $sales = $sales->whereIn('customer_id', $request->customer);
        }

        if ($request->farmer) {
            $sales = $sales->whereIn('farmer_id', $request->farmer);
        }

        if ($request->date) {
            $dates = split_dates($request->date);
            $from = $dates['from'];
            $to = $dates['to'];
            $sales = $sales->whereBetween('created_at', [$from, $to]);
        }


        $statusCheck = ($request->status != null && in_array((int)$request->status, [\App\Invoice::STATUS_PAID, \App\Invoice::STATUS_UNPAID, \App\Invoice::STATUS_PARTIAL_PAID, \App\Invoice::STATUS_RETURNS_RECORDED]));


        if ($statusCheck || $request->invoice_no) {
            $invoice_number = $request->invoice_no;
            $status = (int)$request->status;
            $sales = $sales->whereHas('invoices', function ($q) use ($statusCheck, $status, $invoice_number) {
                if ($invoice_number) {
                    $invoice_number_array = explode("-", $invoice_number);
                    $q->where('invoice_number', "=", $invoice_number_array[0])
                        ->when(count($invoice_number_array) > 1, function ($q) use ($invoice_number_array) {
                            $q->where('invoice_count', $invoice_number_array[1]);
                        });
                }
                if ($statusCheck) {
                    $q->where('status', "=", $status);
                }
            });
        } else {
            $sales = $sales->with('invoices');
        }


        return $sales;
    }
}

if (!function_exists('quotation_base_query')) {
    function quotation_base_query($coop, $request)
    {
        $sales = Sale::where('cooperative_id', $coop)->where('type', 'quotation');

        if ($request->batch_no) {
            $batch_number = $request->batch_no;
            $batch_number_array = explode("-", $batch_number);

            $batch_number_number_sequence = $batch_number_array[0];
            $sales = $sales->where('sale_batch_number', $batch_number_number_sequence)
                ->when(count($batch_number_array) > 1, function ($q) use ($batch_number_array) {
                    $q->where('sale_count', $batch_number_array[1]);
                });
        }

        if ($request->customer) {
            $sales = $sales->whereIn('customer_id', $request->customer);
        }

        if ($request->farmer) {
            $sales = $sales->whereIn('farmer_id', $request->farmer);
        }

        if ($request->date) {
            $dates = split_dates($request->date);
            $from = $dates['from'];
            $to = $dates['to'];
            $sales = $sales->whereBetween('created_at', [$from, $to]);
        }
        return $sales;
    }
}

if (!function_exists('returned_items_data')) {
    function returned_items_data($coop, $request)
    {
        $items = ReturnedItem::where('cooperative_id', $coop);

        if ($request->date) {
            $dates = split_dates($request->date);
            $from = $dates['from'];
            $to = $dates['to'];
            $items = $items->whereBetween('created_at', [$from, $to]);
        }
        $invoice_number = $request->invoice_no;
        if ($invoice_number) {
            $items = $items->whereHas('sale', function ($q) use ($invoice_number) {
                $q->whereHas("invoices", function ($q2) use ($invoice_number) {
                    $invoice_number_array = explode("-", $invoice_number);
                    $q2->where('invoice_number', "=", $invoice_number_array[0])
                        ->when(count($invoice_number_array) > 1, function ($q2) use ($invoice_number_array) {
                            $q2->where('invoice_count', $invoice_number_array[1]);
                        });
                });
            });
        }

        if ($request->customer) {
            $customers = $request->customer;
            $items = $items->whereHas('sale', function ($q) use ($customers) {
                $q->whereIn('customer_id', $customers);
            });
        }

        if ($request->farmer) {
            $farmers = $request->farmer;
            $items = $items->whereHas('sale', function ($q) use ($farmers) {
                $q->whereIn('farmer_id', $farmers);
            });
        }

        return $items->latest();
    }
}


if (!function_exists('formatArrayForSQL')) {
    function formatArrayForSQL($array): string
    {
        $quotedArray = array_map(function ($value) {
            return "'" . $value . "'";
        }, $array);
        $result = implode(',', $quotedArray);
        $result = '(' . $result . ')';

        return $result;
    }
}

if (!function_exists('get_paye')) {
    function get_paye($taxableAmount)
    {
        $remaining_amount = $taxableAmount;
        $total_tax = 0;

        Log::info("Taxable amount: $taxableAmount");
        if ($remaining_amount > MINIMUM_TAXABLE_AMOUNT) {

            $total_tax += (MINIMUM_TAXABLE_AMOUNT_RATE * MINIMUM_TAXABLE_AMOUNT);
            $remaining_amount -= MINIMUM_TAXABLE_AMOUNT;

            Log::info("24,000 band tax $total_tax remaining amount $remaining_amount");
            if ($remaining_amount > TAXABLE_BAND_1_NEXT_8333) {
                $tax_on_current_band = (TAXABLE_BAND_1_NEXT_8333_RATE * TAXABLE_BAND_1_NEXT_8333);
                $total_tax += $tax_on_current_band;
                $remaining_amount -= TAXABLE_BAND_1_NEXT_8333;
                Log::info(" On the next 8,333 band tax $tax_on_current_band remaining amount $remaining_amount");

                if ($remaining_amount > TAXABLE_BAND_2_NEXT_467667) {
                    $tax_on_current_band = (TAXABLE_BAND_2_NEXT_467667_RATE * TAXABLE_BAND_2_NEXT_467667);
                    $total_tax += $tax_on_current_band;
                    $remaining_amount -= TAXABLE_BAND_2_NEXT_467667;
                    Log::info("On the next 467,667 band tax $tax_on_current_band remaining amount $remaining_amount");

                    if ($remaining_amount > TAXABLE_BAND_3_NEXT_300000) {
                        $tax_on_current_band = (TAXABLE_BAND_3_NEXT_300000_RATE * TAXABLE_BAND_3_NEXT_300000);
                        $total_tax += $tax_on_current_band;
                        $remaining_amount -= TAXABLE_BAND_3_NEXT_300000;
                        Log::info("On the next 300,000 band tax $tax_on_current_band remaining amount $remaining_amount");
                        if ($remaining_amount > 0) {
                            $total_tax += (TAXABLE_BAND_4_ABOVE_800000_RATE * $remaining_amount);
                            Log::info("Last Band is on 35%  on $remaining_amount, total tax is $total_tax");
                        }
                    } else {
                        $total_tax += (TAXABLE_BAND_3_NEXT_300000_RATE * $remaining_amount);
                        Log::info("Last Band is on 32.5%  on $remaining_amount, total tax is $total_tax");
                    }
                } else {
                    $total_tax += (TAXABLE_BAND_2_NEXT_467667_RATE * $remaining_amount);
                    Log::info("Last Band is on 30%  on $remaining_amount, total tax is $total_tax");
                }
            } else {
                $total_tax += (TAXABLE_BAND_1_NEXT_8333_RATE * $remaining_amount);
                Log::info("Last Band is on 25%  on $remaining_amount, total tax is $total_tax");
            }

            return $total_tax;
        }

        return MINIMUM_TAXABLE_AMOUNT_RATE * $taxableAmount;
    }
}

if (!function_exists('get_nssf')) {
    function get_nssf($amount)
    {
        $remaining_amount = $amount;
        $total_nssf = 0;
        Log::info("Gross pay amount: $amount");

        if ($amount > NSSF_TIER_1_LOWEST_LIMIT) {
            $total_nssf += NSSF_TIER_1_AND_TIER_2_RATE * NSSF_TIER_1_LOWEST_LIMIT;
            $remaining_amount -= NSSF_TIER_1_LOWEST_LIMIT;

            if ($remaining_amount > NSSF_TIER_2_UPPER_LIMIT) {
                $total_nssf += NSSF_TIER_1_AND_TIER_2_RATE * NSSF_TIER_2_UPPER_LIMIT;
            } else {
                $total_nssf += NSSF_TIER_1_AND_TIER_2_RATE * $remaining_amount;
            }

            return $total_nssf;
        }

        return NSSF_TIER_1_AND_TIER_2_RATE * $amount;
    }
}

if (!function_exists('get_years_from_start')) {
    function get_years_from_start($startYear): array
    {
        $startYear -= 1;
        $current_year = (int)Carbon::now()->format('Y');
        $years = [];
        for ($i = $startYear; $i <= $current_year; $i++) {
            $years[] = $i;
        }
        rsort($years);
        return $years;
    }
}


if (!function_exists('perform_transaction')) {
    function perform_transaction(Transaction $transaction)
    {
        $sender_acc = Account::find($transaction->sender_acc_id);
        $recipient_acc = Account::find($transaction->recipient_acc_id);
        if($transaction->type=='OPERATIONAL_EXPENSE' || 
           $transaction->type == 'WITHDRAWAL'|| 
           $transaction->type == 'FARMER_PAYMENT'){
        
            $sender_acc->balance -= $transaction->amount;
            $sender_acc->save();
        }else{
           $recipient_acc->balance += $transaction->amount;
           $recipient_acc->save();
        }
        $transaction->status = 'COMPLETE';
        # todo: generate stored receipt
        $now = Carbon::now();
        $receiptNumber = "RPT";
        $receiptNumber .= $now->format('Ymd');

        // count today's inventories
        $todaysReceipts = Receipt::where(DB::raw("DATE(created_at)"), $now->format('Y-m-d'))->count();
        $receiptNumber .= str_pad($todaysReceipts + 1, 3, '0', STR_PAD_LEFT);

        // todo: add customer type
        // create receipt
        $receipt = new Receipt();
        $receipt->receipt_number = $receiptNumber;
        $receipt->user_id = Auth::id();
        $receipt->published_at = $now;
        $receipt->save();


        if ($transaction->type == 'DEPOSIT' || $transaction->type == 'WITHDRAWAL') {
            $receiptItem = new ReceiptItem();
            $receiptItem->item_id = $transaction->id;
            $receiptItem->item_type = $transaction->type;
            $receiptItem->price = $transaction->amount;
            $receiptItem->quantity = 1;
            $receiptItem->save();
        }        // create receipt items
        else if ($transaction->subject_type == 'INVOICE') {
            $invoice = NewInvoice::find($transaction->subject_id);
            $invoice_items = $invoice->items;
            foreach ($invoice_items as $item) {
                $receiptItem = new ReceiptItem();
                $receiptItem->receipt_id = $receipt->id;
                $receiptItem->item_id = $item->id;
                $receiptItem->item_type = $item->item_type;
                $receiptItem->price = $item->price;
                $receiptItem->quantity = $item->quantity;
                $receiptItem->save();
            }
        } else {
            $lots = $transaction->lots;
            $lotPricing = $transaction->pricing;
            foreach ($lots as $item) {
                $receiptItem = new ReceiptItem();
                $receiptItem->receipt_id = $receipt->id;
                $receiptItem->item_id = $item->lot_number;
                $receiptItem->item_type = 'LOT';
                $receiptItem->price = $item->quantity * $lotPricing;
                $receiptItem->quantity = $item->quantity;
                $receiptItem->save();
            }
        }

        # receipt number
        $transaction->receipt_id = $receipt->id;

        $transaction->completed_at = Carbon::now();

        $transaction->save();
    }


    if (!function_exists('outerConditionFromFilter')) {
        function outerConditionFromFilter($rawFilter)
        {
            $outerCondition = "";
            if ($rawFilter) {
                $filters = explode(",", $rawFilter);
                $orOpened = false;

                for ($i = 0; $i < count($filters); $i++) {
                    $filter = $filters[$i];
                    if (!$filter) {
                        continue;
                    }
                    $splitFilter = explode("__", $filter);
                    $filterKey = $splitFilter[0];
                    $filterOperator = $splitFilter[1];
                    $filterValue = $splitFilter[2];
                    if ($filterOperator == "has") {
                        $filterOperator = "LIKE";
                        $filterValue = "%" . $filterValue . "%";
                    } else if ($filterOperator == "doesn't have") {
                        $filterOperator = "NOT ILIKE";
                        $filterValue = "%" . $filterValue . "%";
                    }

                    $nextKey = null;
                    if ($i + 1 < count($filters)) {
                        $nextKey = $filters[$i + 1];
                        $nextKey = explode("__", $nextKey)[0];
                    }

                    $prevKey = null;
                    if ($i - 1 >= 0) {
                        $prevKey = $filters[$i - 1];
                        $prevKey = explode("__", $prevKey)[0];
                    }

                    if ($nextKey == $filterKey && !$orOpened) {
                        $outerCondition .= " AND (subquery.$filterKey $filterOperator '$filterValue' ";
                        $orOpened = true;
                        continue;
                    }

                    if ($nextKey != $filterKey && $orOpened) {
                        $outerCondition .= " OR subquery.$filterKey $filterOperator '$filterValue' )";
                        $orOpened = false;
                        continue;
                    }

                    if ($orOpened) {
                        $outerCondition .= " OR subquery.$filterKey $filterOperator '$filterValue' ";
                        continue;
                    }

                    $outerCondition .= " AND subquery.$filterKey $filterOperator '$filterValue' ";
                }
            }
            return $outerCondition;
        }
    }

    if (!function_exists('erpStrFormat')) {
        function erpStrFormat($msg, $vars)
        {
            $vars = (array)$vars;

            $msg = preg_replace_callback('#\{\}#', function ($r) {
                static $i = 0;
                return '{' . ($i++) . '}';
            }, $msg);

            return str_replace(
                array_map(function ($k) {
                    return '{' . $k . '}';
                }, array_keys($vars)),

                array_values($vars),

                $msg
            );
        }
    }

    // transaction sql helpers
    if (!function_exists('getTransactionSubjectSubqueryColumn')) {
        function getTransactionSubjectSubqueryColumn(): string
        {
            return "(
                CASE WHEN t.type = 'OPERATIONAL_EXPENSE'
                    THEN 'OPERATIONAL_EXPENSE'
                WHEN t.subject_type = 'INVOICE'
                    THEN (SELECT i.invoice_number FROM new_invoices i WHERE i.id = t.subject_id)
                WHEN t.subject_type = 'LOT'
                    THEN (SELECT l.lot_number FROM lots l WHERE l.lot_number = t.subject_id)
                WHEN t.subject_type = 'LOT_GROUP'
                    THEN (SELECT g.group_number FROM lot_groups g WHERE g.id= t.subject_id)
                END
            )";
        }
    }

    if (!function_exists('getTransactionSenderSubqueryColumn')) {
        function getTransactionSenderSubqueryColumn(): string
        {
            return "(
                CASE WHEN t.sender_type = 'CASH'
                    THEN 'CASH'
                WHEN t.sender_type = 'COOPERATIVE'
                    THEN (select c.name from cooperatives c where c.id = t.sender_id)
                WHEN t.sender_type = 'MILLER'
                    THEN (select m.name from millers m where m.id = t.sender_id)
                WHEN t.sender_type = 'FARMER'
                    THEN (select CONCAT(u.first_name,' ',u.other_names) from farmers f join users u ON f.user_id = u.id where f.id = t.sender_id)
                WHEN t.sender_type = 'CUSTOMER'
                    THEN (select c.name from customers c where c.id = t.sender_id)
                END
            )";
        }
    }

    if (!function_exists('getTransactionRecipientSubqueryColumn')) {
        function getTransactionRecipientSubqueryColumn(): string
        {
            return "(
                CASE WHEN t.recipient_type = 'CASH'
                    THEN 'CASH'
                WHEN t.recipient_type = 'COOPERATIVE'
                    THEN (select c.name from cooperatives c where c.id = t.recipient_id)
                WHEN t.recipient_type = 'MILLER'
                    THEN (select m.name from millers m where m.id = t.recipient_id)
                WHEN t.recipient_type = 'FARMER'
                    THEN (select CONCAT(u.first_name,' ',u.other_names) from farmers f join users u ON f.user_id = u.id where f.id = t.recipient_id)
                END
            )";
        }
    }

    if (!function_exists('download_pdf2')) {
        function download_pdf2($columns, $data)
        {
            $pdf_view = $data['pdf_view'];
            $title = $data['title'];
            $period = Carbon::now()->format('D, d M Y  H:i:s');
            $records = $data['records'];
            $summation = $data['summation'] ?? null;
            $pdf = app('dompdf.wrapper');
            $pdf->setPaper('letter', $data['orientation']);
            $pdf->loadView('pdf.export', compact('title', 'period', 'records', 'columns', 'summation'));
            $file_name = $data['filename'];
            return $pdf->download($file_name . '.pdf');
        }
    }

    if (!function_exists('print_collection_receipt')) {

        function print_collection_receipt($columns, $data)
        {
            $pdf_view = $data['pdf_view'];
            $title = $data['title'];
            $period = Carbon::now()->format('D, d M Y  H:i:s');
            $records = $data['records'];
            $logo = $data['logo'];
            $qr_code = $data['qr_code'];
            $summation = $data['summation'] ?? null;
            $pdf = app('dompdf.wrapper');
            $pdf->setPaper('letter', $data['orientation']);
            $pdf->loadView('pdf.collection_receipt', compact('title', 'period', 'records', 'columns', 'summation','logo','qr_code'));
            $file_name = $data['filename'];
            return $pdf->download($file_name . '.pdf');
        }
    }
}
