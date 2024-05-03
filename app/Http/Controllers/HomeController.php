<?php

namespace App\Http\Controllers;

use App\Collection;
use App\CoopEmployee;
use App\Country;
use App\Cow;
use App\Farmer;
use App\IncomeAndExpense;
use App\Production;
use App\RawMaterialSupplyHistory;
use App\Sale;
use App\User;
use App\VetBooking;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        if ($request->date) {
            $dates = split_dates($request->date);
            $start = $dates['from'];
            $end = $dates['to'];
        } else {
            $end = Carbon::now()->format('Y-m-d');
            $start = Carbon::now()->subMonth()->format('Y-m-d');
        }

        $user = Auth::user();

        $data = $user->hasRole('farmer') ? $this->farmer_data($start, $end, $user) :
            $this->coop_admin_weekly_analytics($start, $end, $user);
        return view('dashboard', compact('data'));
    }


    private function coop_admin_weekly_analytics($start, $end, User $logged_in_user)
    {
        $cooperative = $logged_in_user->cooperative->id;
        //collections for the last one week.
        $collections = Collection::where('cooperative_id', $cooperative)
            ->whereBetween('date_collected', [$start, $end])
            ->latest()->sum('quantity');
        if ($collections) {
            $collections = number_format($collections);
        } else {
            $collections = '0.00';
        }
        //bookings
        $bookings = VetBooking::where('cooperative_id', $cooperative)
            ->whereBetween('event_end', [$start, $end])
            ->latest()->limit(15)
            ->get();
        $vet_count = DB::select("SELECT COUNT(*) as vets FROM users
                                    JOIN model_has_roles mr ON mr.model_id = users.id JOIN roles
                                    ON roles.id = mr.role_id
                        WHERE roles.name = 'vet' AND users.cooperative_id='$cooperative'")[0]->vets;

        //count farmers
        $farmers_count = (array)DB::select("SELECT COUNT(*) as farmers_count FROM farmers
                                    JOIN users ON users.id = farmers.user_id
                                 WHERE  users.cooperative_id='$cooperative'
                                 GROUP BY users.cooperative_id")[0]->farmers_count;

        //count cooperatives
        $cooperative_employees = DB::select("SELECT COUNT(*) employees_count FROM coop_employees JOIN users
                              ON coop_employees.user_id = users.id WHERE users.cooperative_id='$cooperative'")[0]->employees_count;

        $sales = DB::select(
            "select sum((si.amount * si.quantity) - si.discount) as sales from sales s
                    join sale_items si on s.id = si.sales_id where s.cooperative_id = '$cooperative'
                    and (s.date BETWEEN  '$start' and '$end')"
        )[0]->sales;

        //employees
        $employees = CoopEmployee::select('coop_employees.id')
            ->join('users', 'users.id', '=', 'coop_employees.user_id')
            ->where('users.cooperative_id', $cooperative)
            ->count();

        $stock_value = Production::get_stock_value($cooperative);

//        $revenue = DB::select("
//        SELECT SUM((item.amount * item.quantity) -item.discount) as revenue from sale_items item join
//        sales sale on sale.id = item.sales_id where sale.cooperative_id = '$cooperative' and (sale.date
//        between '$start' and '$end')
//        ")[0]->revenue;
        $expense_income = IncomeAndExpense::select('income', 'expense')
            ->where('cooperative_id', $cooperative)
            ->whereBetween('date', [$start, $end]);

        $income = $expense_income->sum('income');
        $expense = $expense_income->sum('expense');
        $profit_margins = $income - $expense;

        if (abs($profit_margins) > 1000000) {
            $profit_margins = number_format($profit_margins / 1000000, 2, '.', ',') . "M";
        } else {
            $profit_margins = number_format($profit_margins / 1000, 2, '.', ',') . "K";
        }

        if (abs($income) > 1000000) {
            $income = number_format($income / 1000000, 2, '.', ',') . "M";
        } else {
            $income = number_format($income / 1000, 2, '.', ',') . "K";
        }

        if (abs($expense) > 1000000) {
            $expense = number_format($expense / 1000000, 2, '.', ',') . "M";
        } else {
            $expense = number_format($expense / 1000, 2, '.', ',') . "K";
        }

        if ($sales > 1000000) {
            $sales = number_format($sales / 1000000, 2, '.', ',') . "M";
        } else {
            $sales = number_format($sales / 1000, 2, '.', ',') . "K";
        }

        if ($stock_value > 1000000) {
            $stock_value = number_format($stock_value / 1000000, 2, '.', ',') . "M";
        } else {
            $stock_value = number_format($stock_value / 1000, 2, '.', ',') . "K";
        }


        //farmers gender distribution
        $gender_distribution = (array)DB::select("select
            count(case when gender='M' then 1 end) as male,
            count(case when gender='F' then 1 end) as female,
            count(case when gender='X' then 1 end) as other
            from farmers f
            join users u on u.id = f.user_id
            join model_has_roles mr on mr.model_id = u.id
            join roles r on r.id = mr.role_id
            where r.name = 'farmer' and u.cooperative_id = '$cooperative' ")[0];
        return (object)[
            "bookings" => $bookings,
            "collections" => $collections,
            "farmers_count" => $farmers_count[0],
            "employees_count" => $cooperative_employees,
            "start" => Carbon::parse($start)->format('M d'),
            "end" => Carbon::parse($end)->format('M d'),
            "gender" => $gender_distribution,
            "income" => $income,
            "expense" => $expense,
            "vets" => $vet_count,
            "sales" => $sales,
            "profit" => $profit_margins,
            "employees" => $employees,
            "stock_value" => $stock_value

        ];
    }

    private function farmer_data($start, $end, User $logged_in_user): object
    {
        $total_livestock = Cow::where('farmer_id', $logged_in_user->farmer->id)->count();
        $bookings = VetBooking::where('farmer_id', $logged_in_user->id)
            ->where('cooperative_id', $logged_in_user->cooperative->id)
            ->whereBetween('event_end', [$start, $end])
            ->latest()->limit(15)->get();
        $products_supplied = DB::table('farmers_products')->select('product_id')
            ->where('farmer_id', $logged_in_user->id)->count();
        $wallet = Wallet::where('farmer_id', $logged_in_user->farmer->id)->first();
        $all_collections = Collection::where('farmer_id', $logged_in_user->farmer->id)
            ->whereBetween('date_collected', [$start, $end]);
        $collection_quantity = implode(',', $all_collections->pluck('quantity')->toArray());
        $collections = $all_collections->orderBy('date_collected', 'desc')->limit(2)->get();
        return (object)[
            "bookings" => $bookings,
            "product_supplied" => $products_supplied,
            "total_livestock" => $total_livestock,
            "wallet" => $wallet,
            "collections" => $collections,
            "collection_quantity" => $collection_quantity,
            "start" => $start,
            "end" => $end
        ];
    }


    public function dashboard_data(Request $request)
    {
        return $this->weekly_report($request);
    }

    private function weekly_report($request)
    {
        $logged_in_user = Auth::user();
        $cooperative = $logged_in_user->cooperative->id;

        //farmers gender distribution
        $gender_distribution = (array)DB::select("select
            count(case when gender='M' then 1 end) as male,
            count(case when gender='F' then 1 end) as female,
            count(case when gender='X' then 1 end) as other
            from farmers f
            join users u on u.id = f.user_id
            join model_has_roles mr on mr.model_id = u.id
            join roles r on r.id = mr.role_id
            where r.name = 'farmer' and u.cooperative_id = '$cooperative' ")[0];

        $isFarmer = $logged_in_user->hasRole('farmer');

        if ($isFarmer) {
            $res = [
                "collections" => $this->get_product_collection($request, $logged_in_user, true ),
            ];

        } else {
            $res = [
                "gender" => $gender_distribution,
                "collections" => $this->get_product_collection($request,$logged_in_user, false),
                "sales" => $this->sales_distribution($cooperative, $request)
            ];

        }

        return json_encode($res);
    }

    private function get_product_collection(Request $request, User $user, $isFarmer = false)
    {
        if ($request->date) {
            $dates = split_dates($request->date);
            $start = $dates['from'];
            $end = $dates['to'];
        } else {
            $end = Carbon::now()->format('Y-m-d');
            $start = Carbon::now()->subWeek()->format('Y-m-d');
        }
        $cooperative = $user->cooperative_id;

        $query = "SELECT SUM(c.quantity) as quantity, SUM(c.quantity - c.available_quantity) as sold,
            p.name as product from collections c join products p on c.product_id = p.id";

        if ($isFarmer) {
            $farmerId = $user->farmer->id;
            $query .= " WHERE c.farmer_id = '$farmerId'";
        } else {
            $query .= " WHERE c.cooperative_id = '$cooperative'";
        }

        $query .= " AND c.date_collected BETWEEN '$start' AND '$end'
            GROUP BY product ORDER BY product LIMIT 15";

        $collections = DB::select($query);


        return $collections;
    }


    private function sales_distribution($cooperative, $request)
    {
        if ($request->date) {
            $dates = split_dates($request->date);
            $start = $dates['from'];
            $end = $dates['to'];
        } else {
            $end = Carbon::now()->format('Y-m-d');
            $start = Carbon::now()->subYear()->format('Y-m-d');
        }
        $cooperative_sales = DB::select("
        SELECT SUM((item.amount * item.quantity) -item.discount) AS total_amount, MONTH(sale.date) AS month,
               YEAR(sale.date) AS year FROM sale_items item JOIN
               sales sale ON sale.id = item.sales_id 
               WHERE sale.cooperative_id = '$cooperative' AND (sale.date BETWEEN '$start' AND'$end')
               GROUP BY month,year ORDER BY year ASC,month ASC
       ");

        $sales = [];
        foreach ($cooperative_sales as $sale) {
            $sales[] = (object)[
                "amount" => number_format($sale->total_amount, 2, '.', ''),
                "period" => convert_month_to_name($sale->month) . ' ' . $sale->year,
            ];
        }

        return $sales;
    }


}
