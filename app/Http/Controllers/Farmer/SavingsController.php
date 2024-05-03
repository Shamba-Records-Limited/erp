<?php

namespace App\Http\Controllers\Farmer;

use App\Events\AuditTrailEvent;
use App\Http\Controllers\Controller;
use App\Http\Traits\FinancialProducts;
use App\SavingAccount;
use App\SavingInstallment;
use App\SavingType;
use App\Wallet;
use App\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class SavingsController extends Controller
{
    use FinancialProducts;

    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        return $this->get_savings('as-farmer.wallet.savings.index', Auth::user());
    }

    public function create_saving_account(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            "amount" => "required|regex:/^\d+(\.\d{1,2})?$/",
            "type" => 'required'
        ]);
        $auth_user = Auth::user();
        $farmer = $auth_user->farmer;
        Log::info("Creating/adding saving account by farmer");
        return $this->saving_account($request, $auth_user, $farmer);
    }

    public function installments($saving_account_id)
    {
        return $this->get_saving_installments('as-farmer.wallet.savings.installments', $saving_account_id);
    }



    public function withdraw_from_saving_account(Request $request): \Illuminate\Http\RedirectResponse
    {
        $farmer_id = Auth::user()->farmer->id;
        $request->validate([
            "saving_type" => 'required'
        ]);

        try {
            DB::beginTransaction();

            $matured_savings = SavingAccount::where('farmer_id', $farmer_id)
                ->whereDate('maturity_date', '<=', date('Y-m-d'))
                ->where('saving_type_id', $request->saving_type)
                ->first();

            $amount = $matured_savings->amount + ($matured_savings->amount * ($matured_savings->interest / 100));

            if (update_wallet($request, true, $amount, $farmer_id)) {
                $matured_savings->delete();
                DB::commit();
                $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Saving account updated with '
                    . $amount . ' amount', 'cooperative_id' => Auth::user()->cooperative->id];
                event(new AuditTrailEvent($audit_trail_data));
                toastr()->success('Saving account updated');
                return redirect()->back();
            } else {
                DB::rollBack();
                Log::error("================== Failed to create a saving transaction ==================");
                toastr()->error('OOps! Operation failed');
                return redirect()->back()->withInput();
            }
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Failed to withdraw from saving account  ' .
                $request->amount . ' amount', 'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->error('OOps! Operation failed');
            return redirect()->back()->withInput();
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('OOps! Operation failed');
            return redirect()->back()->withInput();
        }

    }
}
