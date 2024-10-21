<?php

namespace App\Http\Traits;

use App\InsuranceClaim;
use App\InsuranceClaimLimit;
use App\InsuranceClaimStatusTracker;
use App\InsuranceDependant;
use App\InsuranceInstallment;
use App\InsurancePaymentModeAdjustedRate;
use App\InsuranceSubscriber;
use App\InsuranceTransactionHistory;
use App\User;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait Insurance
{

    public function addSubscription(array $request)
    {

        $product = $request['product'];
        $user = $request['user'];
        $period = ceil(abs($request['period'])) ?? 1;
        $expiry_date = Carbon::now()->subDay()->addYears($period);
        $subscription = new InsuranceSubscriber();
        $subscription->farmer_id = $request['farmer'];
        $subscription->insurance_product_id = $request['productId'];
        $subscription->insurance_valuation_id = $request['valuation'];
        $subscription->payment_mode = $request['paymentMode'];
        $subscription->period = $period;
        $subscription->expiry_date = $expiry_date;
        $subscription->interest = $product->interest;
        $subscription->cooperative_id = $user->cooperative_id;
        $subscription->status = InsuranceSubscriber::STATUS_ACTIVE;
        $subscription->penalty = $request['penalty'];
        $subscription->grace_period = $request['gracePeriod'];
        //calculate adjusted premiums
        $adjustedRate = InsurancePaymentModeAdjustedRate::where('cooperative_id', $user->cooperative_id)
            ->where('payment_mode', $request['paymentMode'])->first();
        if ($adjustedRate) {
            $premium = (($adjustedRate->adjusted_rate + 100) * $product->premium) / 100;
        } else {
            $premium = $product->premium;
        }
        $subscription->adjusted_premium = $premium;
        $subscription->save();
        $subscription_id = $subscription->fresh()->id;

        //create installments
        $start_date = Carbon::now()->format('Y-m-d');
        $this->create_insurance_installments($request['paymentMode'], $period, $subscription_id, $user->cooperative_id, $premium, $start_date);
        return $subscription_id;
    }

    private function create_insurance_installments($payment_mode, $period, $subscription_id, $cooperative_id, $premium, $start_date)
    {
        if ($payment_mode == InsuranceSubscriber::MODE_ANNUALLY) {
            $counter = 1;
            while ($counter <= $period) {
                $due_date = Carbon::parse($start_date)->subDay()->addYears($counter);
                $this->save_insurance_installments(
                    $subscription_id,
                    InsuranceInstallment::STATUS_PENDING,
                    $due_date,
                    $cooperative_id,
                    $premium
                );
                $counter++;
            }

        }

        if ($payment_mode == InsuranceSubscriber::MODE_QUARTERLY) {
            $installmentAmount = $premium / 4;
            Log::info("Installment: " . $installmentAmount);
            $months = 3;
            while ($period > 0) {
                $counter = 1;
                while ($counter <= 4) {
                    $due_date = Carbon::parse($start_date)->subDay()->addMonths($months);
                    $this->save_insurance_installments(
                        $subscription_id,
                        InsuranceInstallment::STATUS_PENDING,
                        $due_date,
                        $cooperative_id,
                        $installmentAmount
                    );
                    $counter++;
                    $months += 3;
                }
                $period--;
            }
        }


        if ($payment_mode == InsuranceSubscriber::MODE_MONTHLY) {
            $installmentAmount = round($premium / 12);
            $months_increments = 1;
            while ($period > 0) {
                $counter = 1;
                while ($counter <= 12) {
                    $due_date = Carbon::parse($start_date)->subDay()->addMonths($months_increments);
                    $this->save_insurance_installments(
                        $subscription_id,
                        InsuranceInstallment::STATUS_PENDING,
                        $due_date,
                        $cooperative_id,
                        $installmentAmount
                    );
                    $counter++;
                    $months_increments++;
                }
                $period--;
            }
        }
    }

    private function save_insurance_installments($subscription_id, $status, $due_date, $cooperative_id, $amount)
    {
        $installment = new InsuranceInstallment();
        $installment->cooperative_id = $cooperative_id;
        $installment->status = $status;
        $installment->due_date = $due_date;
        $installment->subscription_id = $subscription_id;
        $installment->amount = $amount;
        $installment->save();
    }

    public function editSubscription($request, $subscription)
    {
        $product = $request['product'];
        $user = $request['user'];
        $period = ceil(abs($request['period'])) ?? 1;
        $expiry_date = Carbon::parse($subscription->created_at)->subDay()->addYears($period);

        $subscription->insurance_product_id = $product->id;
        $subscription->insurance_valuation_id = $request['valuation'];
        $subscription->period = $period;
        $subscription->expiry_date = $expiry_date;
        $subscription->interest = $product->interest;
        $subscription->status = InsuranceSubscriber::STATUS_ACTIVE;
        $subscription->penalty = $request['penalty'];
        $subscription->grace_period = $request['gracePeriod'];

        //calculate adjusted premiums if payment mode is updated
        if (intval($request['paymentMode']) != intval($subscription->payment_mode)) {
            InsuranceInstallment::where('subscription_id', $subscription->id)->delete();
            $adjustedRate = InsurancePaymentModeAdjustedRate::where('cooperative_id', $user->cooperative_id)
                ->where('payment_mode', $request['paymentMode'])->first();
            if ($adjustedRate) {
                $premium = (($adjustedRate->adjusted_rate + 100) * $product->premium) / 100;
            } else {
                $premium = $product->premium;
            }
            $subscription->adjusted_premium = $premium;
            //create installments
            $start_date = Carbon::parse($subscription->created_at)->format('Y-m-d');
            $this->create_insurance_installments($request['paymentMode'], $period, $subscription->id, $user->cooperative_id, $premium, $start_date);
        } else {
            $subscription->adjusted_premium = $product->premium;
        }
        $subscription->payment_mode = $request['paymentMode'];
        $subscription->save();
    }

    public function installments($subscription_id): array
    {
        $installments = InsuranceInstallment::where('subscription_id', $subscription_id)
            ->orderBy('due_date')->get();

        $subscription = InsuranceSubscriber::findOrFail($subscription_id);
        $wallet = Wallet::where('farmer_id', $subscription->farmer->id)->first();
        $current_balance = $wallet->current_balance;
        $available_balance = $wallet->available_balance;
        $total_wallet_balance = $current_balance + $available_balance;

        return [
            "installments" => $installments,
            "subscription" => $subscription,
            "total_wallet_balance" => $total_wallet_balance
        ];
    }

    public function payInstallment(InsuranceInstallment $installment, float $amount, User $user)
    {
        $wallet = Wallet::where('farmer_id', $installment->subscription->farmer->id)->first();
        $current_balance = $wallet->current_balance;
        $available_balance = $wallet->available_balance;
        $remaining_balance = $amount;

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
                InsuranceInstallment::TRX_TYPE,
                InsuranceInstallment::REF_PREFIX . $installment->subscription_id,
                "Subscribe to insurance policy $installment->subscription_id",
                $user->id
            );

            if ($current_balance > $remaining_balance) {
                Log::info('Current balance is more than amount being paid');
                create_account_transaction('Insurance Premium Paid', $remaining_balance, 'Insurance Product Policy No.: ' . $installment->subscription->id . ' Product Name: ' . $installment->subscription->insurance_product->name);
                record_insurance_transaction($installment->subscription->id, $remaining_balance, InsuranceTransactionHistory::TYPE_INSTALLMENT, 'Pay Insurance Installment with current', $user);
                $installment->amount_paid += $remaining_balance;
                $installment->amount -= $remaining_balance;
                $wallet->current_balance -= $remaining_balance;
            } else {
                Log::info('Available balance is more than amount being paid');
                create_account_transaction('Insurance Premium Paid', $remaining_balance, 'Insurance Product Policy No.: ' . $installment->subscription->id . ' Product Name: ' . $installment->subscription->insurance_product->name);
                record_insurance_transaction($installment->subscription->id, $remaining_balance, InsuranceTransactionHistory::TYPE_INSTALLMENT, 'Pay Insurance Installment with whole available balance', $user);
                $installment->amount_paid += $remaining_balance;
                $installment->amount -= $remaining_balance;
                $wallet->available_balance -= $remaining_balance;
            }
            $remaining_balance = 0;
        }

        if ($remaining_balance > $current_balance && $current_balance > 0 && $remaining_balance > 0) {
            Log::info('Wallet balance is less than amount being paid');
            Log::info('Start deducting from current balance');
            //start with current balance
            if ($remaining_balance > $current_balance) {
                $wallet->current_balance = 0;
            } else {
                $wallet->current_balance -= $remaining_balance;
            }
            $remaining_balance -= $current_balance;
            record_wallet_transaction(
                $current_balance,
                $wallet->id,
                InsuranceInstallment::TRX_TYPE,
                InsuranceInstallment::REF_PREFIX . $installment->subscription_id,
                "Subscribe to insurance policy $installment->subscription_id",
                $user->id
            );
            create_account_transaction('Insurance Premium Paid', $current_balance, 'Insurance Product Policy No.: ' . $installment->subscription->id . ' Product Name: ' . $installment->subscription->insurance_product->name);
            record_insurance_transaction($installment->subscription->id, $current_balance, InsuranceTransactionHistory::TYPE_INSTALLMENT, 'Pay Insurance Installment with all current balance partially', $user);
            $installment->amount_paid += $current_balance;
            $installment->amount -= $current_balance;
            $wallet->current_balance = 0;
        }

        if ($available_balance > 0 && $remaining_balance > 0) {
            Log::info('Repay with available balance');
            $amount_deducted = 0;
            //deduct from available balance
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
                InsuranceInstallment::TRX_TYPE,
                InsuranceInstallment::REF_PREFIX . $installment->subscription_id,
                "Subscribe to insurance policy $installment->subscription_id",
                $user->id
            );
            create_account_transaction('Insurance Premium Paid', $amount_deducted, 'Insurance Product Policy No.: ' . $installment->subscription->id . ' Product Name: ' . $installment->subscription->insurance_product->name);
            $installment->amount_paid += $amount_deducted;
            $installment->amount -= $amount_deducted;

            Log::info(sprintf("Total deducted %d ", $amount_deducted));
            Log::info(sprintf("Remaining %d ", $remaining_balance));
            record_insurance_transaction($installment->subscription->id, $amount_deducted, InsuranceTransactionHistory::TYPE_INSTALLMENT, 'Pay Insurance Installment with wallet available balance', $user);

        }

        $installment->status = $installment->amount == 0 ? InsuranceInstallment::STATUS_PAID :
            InsuranceInstallment::STATUS_PARTIALLY_PAID;

        $installment->save();
        $wallet->save();
    }

    public function getSubscriptionDetails($farmerId): array
    {
        $subscription_query = InsuranceSubscriber::where('farmer_id', $farmerId);
        $subscriptions = $subscription_query->with('insurance_product')->get();
        $subscription_ids = $subscription_query->pluck('id');
        $dependants = InsuranceDependant::whereIn('subscription_id', $subscription_ids)->get();

        return ["subscriptions" => $subscriptions, "dependants" => $dependants];
    }

    /**
     * @param InsuranceSubscriber $subscription
     * @param float $amount
     * @param bool $hasClaims
     * @param User $user
     * @param string $description
     * @param string|null $dependant
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function addInsuranceClaim(InsuranceSubscriber $subscription, float $amount, bool $hasClaims, User $user, string $description, ?string $dependant)
    {
        if ($hasClaims) {
            //check the limit
            if ($amount > $subscription->current_limit) {
                toastr()->warning('Client limit is not enough for the claim. Remaining limit is ' . $user->cooperative->currency . ' ' . $subscription->current_limit);
                return redirect()->back();
            } else {
                $subscription->current_limit -= $amount;
            }
        } else {

            $claimLimit = InsuranceClaimLimit::where('product_id', $subscription->insurance_product_id)->first();
            if ($claimLimit) {
                $userLimit = $claimLimit->amount * $subscription->period;
                if ($amount > $userLimit) {
                    toastr()->warning('Client limit is not enough for the claim. Remaining limit is ' . $userLimit);
                    return redirect()->back();
                }
                $subscription->current_limit = $userLimit - $amount;
            } else {
                toastr()->warning('Please configure limit for ' . $subscription->insurance_product->name);
                return redirect()->back();
            }
        }

        $claim = new InsuranceClaim();
        $claim->subscription_id = $subscription->id;
        $claim->amount = $amount;
        $claim->dependant_id = $dependant;
        $claim->description = $description;
        $claim->cooperative_id = $user->cooperative_id;

        $tracker = new InsuranceClaimStatusTracker();
        $tracker->status = InsuranceClaim::STATUS_PENDING;
        $tracker->comment = 'Initial status when claim is registered';

        $claim->save();
        $subscription->save();
        $tracker->claim_id = $claim->refresh()->id;
        $tracker->save();
    }

    public function editInsuranceClaim(float               $amount, InsuranceClaim $claim,
                                       InsuranceSubscriber $subscription, User $user,
                                       string              $newSubscription, ?string $dependant, string $description)
    {
        if ($amount != $claim->amount) {
            if ($amount > $subscription->current_limit) {
                toastr()->warning('Client limit is not enough for the claim. Remaining limit is ' . $user->cooperative->currency . ' ' . $subscription->current_limit);
                return redirect()->back();
            }
            $subscription->current_limit -= ($amount - $claim->amount);
        }

        $claim->subscription_id = $newSubscription;
        $claim->amount = $amount;
        $claim->dependant_id = $dependant;
        $claim->description = $description;
        $claim->save();
        $subscription->save();
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Database\Query\Builder
     */
    public function transactionHistory(Request $request, User $user)
    {
        if ($request->dates) {
            $dates = split_dates($request->dates);
            $from = $dates['from'];
            $to = $dates['to'];

        } else {
            $from = Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');
        }

        $subscriptionId = $request->subscription;
        $type = $request->type;

        $query = InsuranceTransactionHistory::join('insurance_subscribers', 'insurance_subscribers.id', '=', 'insurance_transaction_histories.subscription_id')
            ->where('insurance_transaction_histories.cooperative_id', $user->cooperative_id);

        if (!$user->hasRole('farmer')) {
            $farmerId = $request->farmer;
            if ($farmerId) {
                $query = $query->where('insurance_subscribers.farmer_id', $farmerId);
            }
        }else{
            $query = $query->where('insurance_subscribers.farmer_id', $user->farmer->id);
        }

        if($subscriptionId) {
            $query = $query->where('insurance_subscribers.id', $subscriptionId);
        }

        if ($type) {
            $query = $query->where('insurance_transaction_histories.type', $type);
        }
        if ($request->dates) {
            if ($to == $from) {
                $query = $query->whereDate('insurance_transaction_histories.date', $from);
            } else {
                $query = $query->whereBetween('insurance_transaction_histories.date', [$from, $to]);
            }
        }


        return $query;
    }

}
