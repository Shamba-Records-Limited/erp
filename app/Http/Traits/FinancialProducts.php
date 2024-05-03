<?php

namespace App\Http\Traits;

use App\Events\AuditTrailEvent;
use App\Farmer;
use App\GroupLoan;
use App\GroupLoanRepayment;
use App\LimitRateConfig;
use App\Loan;
use App\LoanApplicationDetail;
use App\LoanInstallment;
use App\LoanSetting;
use App\SavingAccount;
use App\SavingInstallment;
use App\SavingType;
use App\User;
use App\Wallet;
use App\WalletTransaction;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

trait FinancialProducts
{
    use Payment;

    public function applyLoan(Request $request, $farmer_id, $wallet_id, User $auth_user)
    {
        try {
            DB::beginTransaction();
            $farm_tools = $request->farm_tools > 0;
            $cooperative_id = $auth_user->cooperative->id;
            //check farmer limit
            $details = $this->calculateLimit($farmer_id, $auth_user->cooperative_id, $farm_tools);
            Log::info("Loan Limit details ", $details);
            $loan_limit = $details["limit"];
            $amount_to_disburse = $request->amount;
            if ($loan_limit < $amount_to_disburse) {
                Log::warning(sprintf("Loan limit {%f} is lower than applied amount {%f}", $loan_limit, $request->amount));
                toastr()->error('Failed! Your limit is lower than the amount');
                return redirect()->back();
            }
            $type = LoanSetting::find($request->type_id);
            $amount = $request->amount;
            $loans_receivable_amount = (($amount * $type['interest']) / 100) + $amount;
            Log::info('Loans Receivable', [ number_format($loans_receivable_amount) ]);
            //check if the farmer has loan of the same type
            $loan = new Loan();
            $loan_check = Loan::where('farmer_id', $farmer_id)
                ->where('loan_setting_id', $request->type_id)
                ->where('status', '!=', Loan::STATUS_REPAID)
                ->first();
            if ($loan_check && $loan_check->count() > 0) {
                Log::info('Farmer has an existing loan, buying off.');
                Log::info($loan_check);
                //buy off
                //get balance
                $balance = $loan_check->balance;
                $loan->bought_off_loan_id = $loan_check->id;
                $amount += $balance;
                Log::info('New amt ' . $amount);
                //update installments
                LoanInstallment::where('loan_id', $loan_check->id)->update(['status' => 1]);
                //update loan status
                Loan::where('farmer_id', $farmer_id)->where('loan_setting_id', $request->type_id)->where('status', '!=', 2)->update(['status' => 4]);
            }
            $loan->amount = $amount_to_disburse;
            $payable = (($amount * $type['interest']) / 100) + $amount;

            $loan->balance = ceil($payable);
            $config = LimitRateConfig::where('cooperative_id', Auth::user()->cooperative_id)->first();

            $loan->farmer_id = $farmer_id;
            $loan->due_date = now()->addMonths($type['period']);
            $loan->mode_of_payment = $request->mode_of_repayment;
            $loan->interest = $type['interest'];
            $loan->purpose = $request->purpose;
            $loan->status = $amount >= $config->limit_for_approval ? Loan::STATUS_PENDING : Loan::STATUS_APPROVED;
            $loan->loan_setting_id = $request->type_id;
            $loan->save();
            Log::info("Saving Loan Details");
            //save loan details
            $loan_id = $loan->refresh()->id;

            $filename = store_image($request, "supporting_document", $request->supporting_document, "images/loans", 400, 400);

            $this->saveCommercialLoanDetails($details, $loan_id, $auth_user->cooperative_id, $filename);

            //set installments
            $initial_installment_no = $request->mode_of_repayment == LoanSetting::REPAYMENT_MODE_ONE_OFF ? 1 : $type['installments'];
            $installment_no = $type['installments'];
            $installment_period = $type['period'];
            //calculae installment amt
            $divided = ceil($payable / $initial_installment_no);
            $remainder = $payable % $initial_installment_no;

            Log::info("Save installments");
            //insert installment
            $loan_id = $loan->refresh()->id;
            if ($request->mode_of_repayment == LoanSetting::REPAYMENT_MODE_ONE_OFF) {
                $installment = new LoanInstallment();
                $installment->loan_id = $loan_id;
                $installment->date = now()->addMonths($installment_period);
                $installment->amount = $divided;
                $installment->status = 0;
                $installment->save();
            } else {
                while ($installment_no > 0) {
                    $installment = new LoanInstallment();
                    $installment->loan_id = $loan_id;
                    //get installment amt
                    if ($installment_no == $initial_installment_no) {
                        $installment_amount = $divided + $remainder;
                    } else {
                        $installment_amount = $divided;
                    }
                    $installment->amount = ceil($installment_amount);
                    $inst_period = now()->addMonths($installment_no);
                    $installment->date = $inst_period;
                    $installment->status = 0;
                    $installment->save();

                    $installment_no--;

                }
            }

            Log::info("Creating a wallet transaction");
            //save transaction
            $transaction = new WalletTransaction();
            $transaction->wallet_id = $wallet_id;
            $transaction->type = 'Loan Deposit';
            $transaction->amount = $amount_to_disburse;
            $transaction->reference = 'LOAN#' . $loan['id'];
            $transaction->source = 'Loan';
            $transaction->initiator_id = $auth_user->id;
            $transaction->description = 'Loan request deposited to wallet';
            $transaction->save();
            //update wallet
            Log::info("Update wallet balance with +{$amount_to_disburse}");
            $wallet = Wallet::find($wallet_id);
            $wallet->available_balance += $amount_to_disburse;
            $wallet->save();

            Log::info("Update ledger Accounts");
            $farmer = Farmer::find($farmer_id);
            $farmer_names = ucwords(strtolower($farmer->user->first_name . ' ' . $farmer->user->other_names));
            $acc_description = $farmer_names . ' Loan Request';
            $loan_principal = $amount_to_disburse;
            $loan_interest = (($amount * $type['interest']) / 100);
            if (
                create_account_transaction('Loan Awarded', $loan_principal, $acc_description . '(principal)') && 
                create_account_transaction('Loan Interest Outstanding', $loan_interest, $acc_description . '(interest)')
            ) {
                DB::commit();
                $activity = $request->farmer_id ? 'Farmer creating a loan request for farmer id ' . $farmer_id : 'Admin creating a loan request for farmer id ' . $farmer_id;
                $audit_trail_data = ['user_id' => $auth_user->id, 'activity' => $activity, 'cooperative_id' => $cooperative_id];
                event(new AuditTrailEvent($audit_trail_data));

                toastr()->success('Loan Created Successfully');
                return redirect()->back();
            }

            Log::error("Failed to update transaction ledgers");
            toastr()->error('Loan Request Failed');
            return redirect()->back();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            Log::debug($th->getTraceAsString());
            DB::rollback();
            toastr()->error('Oops! Transaction Failed');
            return redirect()->back();
        }
    }

    private function saveCommercialLoanDetails($details, $loanId, $cooperativeId, $filename)
    {
        $loanDetails = new LoanApplicationDetail();
        $loanDetails->loan_id = $loanId;
        $loanDetails->has_farm_tools = $details['has_farm_tools'];
        $loanDetails->has_land = $details['farm_size'] > 0;
        $loanDetails->has_livestock = $details['has_livestock'] > 0;
        $loanDetails->original_rate = $details['original_rate'];
        $loanDetails->rate_applied = $details['rate'];
        $loanDetails->wallet_balance = $details['wallet_balance'];
        $loanDetails->average_cash_flow = $details['average_cash_flow'];
        $loanDetails->pending_payments = $details['pending_payments'];
        $loanDetails->cooperative_id = $cooperativeId;
        $loanDetails->supporting_document = $filename;
        $loanDetails->limit = $details['limit'];
        $loanDetails->save();

    }


    public function get_loan_installments($loan_id, $page)
    {
        $installments = LoanInstallment::where('loan_id', $loan_id)->orderBy('date', 'asc')->get();
        $loan = Loan::findOrFail($loan_id);
        $farmer = ucwords(strtolower($loan->farmer->user->first_name . ' ' . $loan->farmer->user->other_names));
        $phone = '254' . substr($loan->farmer->phone_no, -9);
        $wallet = Wallet::where('farmer_id', $loan->farmer->id)->first();
        $total_account_balances = $wallet->current_balance + $wallet->available_balance;
        return view('pages.' . $page, compact('installments', 'farmer', 'loan_id', 'total_account_balances', 'phone'));
    }

    public function repayLoan(LoanInstallment $loanInstallment): \Illuminate\Http\RedirectResponse
    {
        $loan = $loanInstallment->loan;
        $wallet = Wallet::where('farmer_id', $loan->farmer->id)->first();

        //check if the customer has a loan;
        if ($loan->balance <= 0) {
            Log::info('You do not have a loan balance');
            toastr()->warning('You do not have a loan balance');
            return redirect()->back();
        }
        $user = Auth::user();
        $remaining_balance = $loanInstallment->amount - $loanInstallment->repaid_amount;
        $current_balance = $wallet->current_balance;
        $available_balance = $wallet->available_balance;
        try {
            DB::beginTransaction();

            if (($available_balance + $current_balance) <= 0) {
                Log::info('User does not have amount in the wallet');
                toastr()->warning('Account balance is 0');
                return redirect()->back();
            }

            if ($current_balance > $remaining_balance || $available_balance > $remaining_balance) {
                Log::info('Wallet balance is more than amount being paid');
                record_wallet_transaction(
                    $remaining_balance,
                    $wallet->id,
                    LoanInstallment::TRX_TYPE,
                    LoanInstallment::REF_PREFIX,
                    "Fully Repaid loan",
                    $user->id
                );
                create_account_transaction('Loan Repaid', $remaining_balance, 'Loan repayment');
                $loanInstallment->status = LoanInstallment::STATUS_PAID;
                $loanInstallment->repaid_amount += $remaining_balance;

                if ($current_balance > $remaining_balance) {
                    Log::info('Current balance is more than amount being paid');
                    $wallet->current_balance -= $remaining_balance;
                } else {
                    Log::info('Available balance is more than amount being paid');
                    $wallet->available_balance -= $remaining_balance;
                }

                $loan_balance = $loan->balance - $loanInstallment->repaid_amount;
                if ($loan_balance <= 0) {
                    Log::info("Loan balance is 0");
                    $loan->status = Loan::STATUS_REPAID;
                    $loan->balance = 0;

                } else {
                    Log::info("Loan balance is:  " . $loan_balance);
                    $loan->status = Loan::STATUS_PARTIAL_REPAYMENT;
                    $loan->balance -= $remaining_balance;
                }

                $remaining_balance = 0;
            }

            //deduct from current balance
            if ($remaining_balance > $current_balance && $current_balance > 0 && $remaining_balance > 0) {
                Log::info('Wallet balance is less than amount being paid');
                Log::info('Start deducting from current balance');
                if ($remaining_balance > $current_balance) {
                    $wallet->current_balance = 0;
                } else {
                    $wallet->current_balance -= $remaining_balance;
                }
                $remaining_balance -= $current_balance;
                record_wallet_transaction(
                    $current_balance,
                    $wallet->id,
                    LoanInstallment::TRX_TYPE,
                    LoanInstallment::REF_PREFIX,
                    "Repay Loan With Current Account Balance, Deduct from Current Account Balance",
                    $user->id);
                create_account_transaction('Loan Repaid', $current_balance, 'Loan repayment');
                $loanInstallment->repaid_amount += $current_balance;
                $loan->balance -= $current_balance;
            }

            //deduct from available balance
            if ($available_balance > 0 && $remaining_balance > 0) {
                Log::info('Repay with available balance');
                $amount_deducted = 0;
                if ($remaining_balance > $available_balance) {
                    $wallet->available_balance = 0;
                    $amount_deducted += $available_balance;
                } else {
                    $wallet->available_balance -= $remaining_balance;
                    $amount_deducted += $remaining_balance;
                }
                $remaining_balance -= $available_balance;
                record_wallet_transaction(
                    $amount_deducted,
                    $wallet->id,
                    LoanInstallment::TRX_TYPE,
                    LoanInstallment::REF_PREFIX,
                    "Repay Loan With Available Account Balance, after other deductions",
                    $user->id);
                create_account_transaction('Loan Repaid', $amount_deducted, 'Loan repayment');
                $loanInstallment->repaid_amount += $amount_deducted;
                $loan->balance -= $amount_deducted;
                Log::info(sprintf("Total deducted %d ", $amount_deducted));
                Log::info(sprintf("Remaining %d ", $remaining_balance));
            }
            $loan->status = $loan->balance == 0 ? Loan::STATUS_REPAID : Loan::STATUS_PARTIAL_REPAYMENT;
            $loanInstallment->status = $remaining_balance > 0 ? LoanInstallment::STATUS_PARTIALLY_PAID : LoanInstallment::STATUS_PAID;

            $loan->save();
            $loanInstallment->save();
            $wallet->save();
            DB::commit();
            $audit_trail_data = ['user_id' => $user->id, 'activity' => "Repaid Loan {$loan->id} installment {$loanInstallment->id}", 'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Loan Updated');
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Error in updating transaction" . $e->getMessage());
            toastr()->error('Oops Operation Fail');
            return redirect()->back();
        }
    }

    public function repay_loan_via_mpesa(LoanInstallment $loanInstallment, $phone)
    {
        $loan = $loanInstallment->loan;
        if ($loan->balance <= 0) {
            Log::info('You do not have a loan balance');
            toastr()->warning('You do not have a loan balance');
            return redirect()->back();
        }
        $loanInstallment->status = LoanInstallment::STATUS_PENDING;
        $loanInstallment->save();
        $this->stk_push($phone, $loanInstallment->amount, "Repay Loan #$loanInstallment->loan_id", LoanInstallment::class, $loanInstallment->id, $loanInstallment->loan->farmer);
    }

    public function repay_group_loan($group_loan_id, $repaid_amount, $source): \Illuminate\Http\RedirectResponse
    {
        $group_loan = GroupLoan::findOrFail($group_loan_id);
        $wallet = Wallet::where('farmer_id', $group_loan->farmer->id)->first();
        if ($group_loan->balance <= 0) {
            Log::info('Group Loan Repayment: You do not have a loan balance');
            toastr()->warning('You do not have a loan balance');
            return redirect()->back();
        }
        $user = Auth::user();
        $remaining_balance = $group_loan->balance - $repaid_amount;
        $current_balance = $wallet->current_balance;
        $available_balance = $wallet->available_balance;
        if ($current_balance > $repaid_amount || $available_balance > $repaid_amount) {
            Log::info('Group Loan Repayment: Wallet balance is more than amount being paid');
            record_wallet_transaction(
                $repaid_amount,
                $wallet->id,
                LoanInstallment::TRX_TYPE,
                LoanInstallment::REF_PREFIX,
                "Repay Group Loan",
                $user->id
            );
            create_account_transaction('Loan Repaid', $repaid_amount, 'Group Loan Repayment: Repay Group Loan');
            $group_loan->status = $remaining_balance == 0 ? GroupLoan::STATUS_PAID : GroupLoan::STATUS_PARTIALLY_PAID;

            if ($current_balance > $repaid_amount) {
                Log::info('Group Loan Repayment: Current balance is more than amount being paid');
                $wallet->current_balance -= $repaid_amount;
            } else {
                Log::info('Group Loan Repayment: Available balance is more than amount being paid');
                $wallet->available_balance -= $repaid_amount;
            }
        }
        $group_loan_repayment = new GroupLoanRepayment();
        $group_loan_repayment->group_loan_id = $group_loan_id;
        $group_loan_repayment->amount = $repaid_amount;
        $group_loan_repayment->status = GroupLoanRepayment::STATUS_COMPLETED;
        $group_loan_repayment->initiated_by_id = $user->id;
        $group_loan_repayment->source = $source;
        $group_loan_repayment->cooperative_id = $user->cooperative_id;
        $group_loan_repayment->save();
        $group_loan->balance = $group_loan->balance - $repaid_amount;
        $wallet->save();
        $group_loan->save();
        DB::commit();
        $audit_trail_data = ['user_id' => $user->id, 'activity' => "Repaid Loan {$group_loan->id} with {$user->cooperative->currency} {$repaid_amount}", 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Loan Updated');
        return redirect()->back();
    }

    public function repay_group_loan_by_mpesa($group_loan_id, $repaid_amount, $source, User $user, $phone)
    {
        $group_loan = GroupLoan::findOrFail($group_loan_id);
        if ($group_loan->balance <= 0) {
            Log::info('Group Loan Repayment: You do not have a loan balance');
            toastr()->warning('You do not have a loan balance');
            return redirect()->back();
        }

        $group_loan_repayment = new GroupLoanRepayment();
        $group_loan_repayment->group_loan_id = $group_loan_id;
        $group_loan_repayment->amount = $repaid_amount;
        $group_loan_repayment->status = GroupLoanRepayment::STATUS_INITIATED;
        $group_loan_repayment->initiated_by_id = $user->id;
        $group_loan_repayment->source = $source;
        $group_loan_repayment->cooperative_id = $user->cooperative_id;
        $group_loan_repayment->save();
        $phone = $phone == null ? $user->farmer->phone_no : $phone;
        Log::debug("Group Loan Initiating MPESA Payment");
        $this->stk_push($phone, $repaid_amount, "Repay Group Loan #$group_loan_id", GroupLoanRepayment::class, $group_loan_repayment->refresh()->id, $group_loan->farmer);
    }

    public function get_savings($page, User $user)
    {
        $cooperative = $user->cooperative_id;
        $saving_types = SavingType::where('cooperative_id', $cooperative)->get();

        if ($user->hasRole('farmer')) {
            Log::info("Retrieving savings initiated by the farmer");
            $farmer_id = $user->farmer->id;
            $savings = DB::select("select sa.id as id, sa.status as status, sa.amount, sa.date_started, sa.maturity_date,
                            st.type as saving_type, sa.interest as interest_rate
                            from saving_accounts sa join saving_types st on sa.saving_type_id = st.id 
                            where amount > 0 and sa.farmer_id = '$farmer_id' order by sa.status LIMIT 100");
            $matured_savings = SavingAccount::where('saving_accounts.farmer_id', $farmer_id)
                ->where('saving_accounts.status', SavingAccount::STATUS_ACTIVE)
                ->whereDate('saving_accounts.maturity_date', '<=', date('Y-m-d'))->get();
            $wallet_balance = Wallet::where('farmer_id', $farmer_id)->first();
            $farmer_savings = SavingAccount::where('farmer_id', $farmer_id)->get();
            return view('pages.' . $page, compact('savings', 'matured_savings', 'saving_types', 'wallet_balance', 'farmer_savings'));
        } else {
            Log::info("Retrieving savings initiated by admin");
            $matured_savings = SavingAccount::join('farmers', 'farmers.id', '=', 'saving_accounts.farmer_id')
                ->join('users', 'users.id', '=', 'farmers.user_id')
                ->where('users.cooperative_id', $cooperative)
                ->where('saving_accounts.status', SavingAccount::STATUS_ACTIVE)
                ->whereDate('saving_accounts.maturity_date', '<=', date('Y-m-d'))->get();
            $savings = DB::select("select sa.id as id, sa.status as status, u.first_name, u.other_names, sa.amount, sa.date_started, sa.maturity_date,
                            st.type as saving_type, sa.interest as interest_rate
                            from saving_accounts sa join saving_types st on sa.saving_type_id = st.id 
                            join farmers f on sa.farmer_id = f.id
                            join users u on f.user_id = u.id where amount > 0 and u.cooperative_id = '$cooperative' order by sa.status asc LIMIT 100");
            $farmers = DB::select("SELECT f.id as id, u.first_name, u.other_names FROM farmers f
                                    JOIN users u on f.user_id = u.id WHERE u.cooperative_id = '$cooperative'");
            return view('pages.' . $page, compact('savings', 'farmers', 'matured_savings', 'saving_types'));
        }

    }

    public function saving_account(Request $request, User $auth_user, \App\Farmer $farmer): \Illuminate\Http\RedirectResponse
    {
        $farmer_id = $farmer->id;
        $farmer_names = ucwords(strtolower($auth_user->first_name . ' ' . $auth_user->other_names));
        try {
            DB::beginTransaction();

            $wallet_balance = Wallet::where('farmer_id', $farmer_id)->first();

            if ($wallet_balance) {
                $wallet_balance = $wallet_balance->current_balance;
            } else {
                Log::error('================== Failed! Farmer does not have a wallet. ' .
                    $request->type . ' ==================');
                toastr()->error('OOps! Farmer does not have enough money in their wallet. Current balance 0');
                return redirect()->back()->withInput()->withErrors(['farmer' => 'Farmer does not have enough money in their wallet. Current balance 0']);
            }

            if ($request->amount > $wallet_balance) {
                Log::error('Farmer does not  have enough money in your wallet. Current balance is '
                    . $wallet_balance);
                toastr()->error('OOps! Farmer does not  have enough money in your wallet. Current balance is ' . $wallet_balance);
                return redirect()->back()->withInput()->withErrors(
                    ['amount' => 'Farmer do not have enough money in their wallet. Current balance is ' . $wallet_balance]);
            }

            $check_farmer_saving_exist = SavingAccount::where('farmer_id', $farmer_id)
                ->where('saving_type_id', $request->type)
                ->whereDate('maturity_date', '>=', date('Y-m-d'))->first();
            if ($check_farmer_saving_exist) {
                $check_farmer_saving_exist->amount += $request->amount;
                $check_farmer_saving_exist->save();
                $created_saving_account_id = $check_farmer_saving_exist->id;
            } else {
                $saving_type = SavingType::find($request->type);
                if ($saving_type) {
                    $saving_account = new SavingAccount();
                    $saving_account->amount = $request->amount;
                    $saving_account->date_started = date('Y-m-d');
                    $saving_account->maturity_date = Carbon::now()->addMonths($saving_type->period);
                    $saving_account->interest = $saving_type->interest;
                    $saving_account->farmer_id = $farmer_id;
                    $saving_account->saving_type_id = $request->type;
                    $saving_account->save();
                    $created_saving_account_id = $saving_account->fresh()->id;

                } else {
                    Log::error('================== Failed! Saving Type selected does not exist ' .
                        $request->type . ' ==================');
                    toastr()->error('OOps! Saving Type selected does not exist. Saving Type selected does not exist');
                    return redirect()->back()->withInput()->withErrors([]);
                }
            }

            $acc_description = $farmer_names . ' savings';
            $wallet_trx = update_wallet($request, false, 0, $farmer_id);
            if (
                $wallet_trx !== false &&
                create_account_transaction('Savings Deposit', $request->amount, $acc_description)
            ) {
                $saving_installment = new SavingInstallment();
                $saving_installment->saving_id = $created_saving_account_id;
                $saving_installment->wallet_transaction_id = $wallet_trx;
                $saving_installment->save();

                DB::commit();
                $audit_trail_data = ['user_id' => $auth_user->id, 'activity' => 'Saving account updated with '
                    . $request->amount . ' amount', 'cooperative_id' => $auth_user->cooperative->id];
                event(new AuditTrailEvent($audit_trail_data));
                toastr()->success('Saving account updated');
                return redirect()->back();
            } else {
                DB::rollBack();
                Log::error("================== Failed to create a saving transaction ==================");
                toastr()->error('OOps! Operation failed. Failed to create a saving transaction');
                return redirect()->back()->withInput()->withErrors([]);
            }

        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);
            $audit_trail_data = ['user_id' => $auth_user->id, 'activity' => 'Failed to update saving account with ' .
                $request->amount . ' amount', 'cooperative_id' => $auth_user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->error('OOps! Operation failed. 1');
            return redirect()->back()->withInput()->withErrors([]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);
            $audit_trail_data = ['user_id' => $auth_user->id, 'activity' => 'Failed to update saving account with ' .
                $request->amount . ' amount', 'cooperative_id' => $auth_user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->error('OOps! Operation failed. 2');
            return redirect()->back()->withInput()->withErrors([]);
        }
    }

    public function get_saving_installments($page, $saving_account_id)
    {
        $savingAccount = SavingAccount::findOrFail($saving_account_id);
        $saving_type = $savingAccount->saving_type;
        $user = $savingAccount->farmer->user;
        $farmer = ucwords(strtolower($user->first_name . ' ' . $user->other_names));
        $saving_statement = DB::select("
            SELECT wt.amount, wt.created_at AS date, wt.reference  FROM saving_accounts sa 
            INNER JOIN saving_installments si ON sa.id = si.saving_id
            INNER JOIN wallet_transactions wt ON si.wallet_transaction_id = wt.id
            WHERE sa.id = '$savingAccount->id'
        ");

        return view('pages.' . $page, compact('saving_type', 'saving_statement', 'farmer', 'saving_account_id'));
    }

    public function calculateLimit(string $farmer_id, $cooperative, bool $has_farm_tools): array
    {
        $farmer = \App\Farmer::findOrFail($farmer_id);
        $wallet = Wallet::getWalletByFarmerId($farmer_id);
        if($wallet == null){
            Log::info("FarmerId {$farmer->id} does not have a wallet");
            $wallet = default_wallet($farmer->id, 0);
        }
        $average_cash_flow = Wallet::averageCashFlow(6, $wallet->id);
        $wallet_balance = $wallet->available_balance;
        $pending_payments = $wallet->current_balance;

        $today = Carbon::now()->format('Y-m-d');

        $has_due_loans = Loan::where('farmer_id', $farmer_id)
                ->whereIn('status', [Loan::STATUS_PARTIAL_REPAYMENT, Loan::STATUS_APPROVED])
                ->whereDate('due_date', '<=', $today)
                ->count() > 0;

        $repaid_loans = Loan::where('farmer_id', $farmer_id)
                ->where('status', '=', Loan::STATUS_REPAID)
                ->count() > 0;

        $rate = LimitRateConfig::where('cooperative_id', $cooperative)->first()->rate;
        $farm_size = $farmer->farm_size;
        $livestock = $farmer->livestock()->count();
        $original_rate = $rate;

        $cash = $average_cash_flow + $wallet_balance + $pending_payments;
        Log::info("Farmer Id $farmer_id : Average Cash flow: $average_cash_flow");
        Log::info("Farmer Id $farmer_id : Wallet Balance: $wallet_balance");
        Log::info("Farmer Id $farmer_id : Pending Payments : $pending_payments");

        $loan_history = 'None';
        if ($has_due_loans) {
            Log::info("Farmer Id $farmer_id : Has past due loans, rate ($rate) to decrease by 2%");
            $rate -= 2;
            $loan_history = 'Bad';
        } else {
            if ($repaid_loans) {
                Log::info("Farmer Id $farmer_id : Repaid last loan  on time, rate ($rate) to increase by 1%");
                $rate += 1;
                $loan_history = 'Good';
            }
        }

        Log::info("Farmer Id $farmer_id : Loan History: $loan_history");
        if ($livestock > 0) {
            Log::info("Farmer Id $farmer_id : Has livestock, rate ($rate) to increase by 1%");
            $rate += 1;
        }

        if ($farm_size > 0) {
            Log::info("Farmer Id $farmer_id : Has farm, rate ($rate) to increase by 1%");
            $rate += 1;
        }

        if ($has_farm_tools) {
            Log::info("Farmer Id $farmer_id : Has farm tools, rate ($rate) to increase by 1%");
            $rate += 1;
        }


        $limit = ceil(($cash * $rate) / 100);

        Log::info("Farmer Id $farmer_id : Limit $limit");

        return [
            'limit' => $limit,
            'rate' => $rate,
            'average_cash_flow' => $average_cash_flow,
            'wallet_balance' => $wallet_balance,
            'pending_payments' => $pending_payments,
            'has_livestock' => $livestock > 0,
            'farm_size' => $farm_size,
            'has_farm_tools' => $has_farm_tools,
            'loan_history' => $loan_history,
            'original_rate' => $original_rate
        ];

    }


}
