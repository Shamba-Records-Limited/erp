<?php

namespace App\Http\Controllers\Farmer;

use App\Events\AuditTrailEvent;
use App\Http\Controllers\Controller;
use App\Http\Traits\Insurance;
use App\InsuranceBenefit;
use App\InsuranceClaim;
use App\InsuranceClaimLimit;
use App\InsuranceClaimStatusTracker;
use App\InsuranceDependant;
use App\InsuranceInstallment;
use App\InsurancePaymentModeAdjustedRate;
use App\InsuranceProduct;
use App\InsuranceSubscriber;
use App\InsuranceValuation;
use App\Wallet;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class InsuranceController extends Controller
{
    use Insurance;

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function paymentModeAdjustments()
    {

        $configs = InsurancePaymentModeAdjustedRate::adjustmentRates(Auth::user()->cooperative_id);
        return view('pages.as-farmer.insurance.payment_mode_configs', compact('configs'));

    }

    public function benefits()
    {
        $benefits = InsuranceBenefit::benefits(Auth::user()->cooperative_id);
        return view('pages.as-farmer.insurance.benefits', compact('benefits'));
    }

    public function products()
    {
        $products = InsuranceProduct::products(Auth::user()->cooperative_id);
        return view('pages.as-farmer.insurance.products', compact('products'));
    }

    public function valuations()
    {
        $user = Auth::user();
        $valuations = InsuranceValuation::where('cooperative_id', $user->cooperative_id)
            ->where('farmer_id', $user->farmer->id)->get();
        return view('pages.as-farmer.insurance.valuations', compact('valuations'));
    }

    public function subscriptions()
    {
        $user = Auth::user();
        $farmer_id = $user->farmer->id;
        $products = DB::select("SELECT id, name from insurance_products where cooperative_id = '$user->cooperative_id'");
        $valuations = DB::select("SELECT id, type FROM insurance_valuations WHERE farmer_id = '$farmer_id'");
        $subscriptions = InsuranceSubscriber::where('farmer_id', $farmer_id)->get();
        return view('pages.as-farmer.insurance.subscriptions', compact('valuations', 'products', 'subscriptions'));

    }

    public function newSubscription(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'product' => 'required',
            'valuation' => 'sometimes|nullable|string',
            'payment_mode' => 'required',
            'period' => 'sometimes|nullable|integer|min:1',
            'penalty' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'grace_period' => 'required|integer|min:1|max:365',

        ]);
        $user = Auth::user();

        $isSubscribed = InsuranceSubscriber::where('farmer_id', $user->farmer->id)
                ->where('insurance_product_id', $request->product)->count() > 0;

        if ($isSubscribed) {
            toastr()->error("You have already subscribed to this product");
            return redirect()->back();
        }

        $product = InsuranceProduct::findOrFail($request->product);
        try {
            DB::beginTransaction();
            $req = [
                "farmer" => $user->farmer->id,
                "productId" => $product->id,
                "valuation" => $request->valuation,
                "paymentMode" => $request->payment_mode,
                "period" => $request->period,
                "product" => $product,
                "user" => $user,
                "penalty" => $request->penalty,
                "gracePeriod" => $request->grace_period
            ];

            $subscription_id = $this->addSubscription($req);

            DB::commit();
            $audit_trail_data = ['user_id' => $user->id,
                'activity' => 'Farmer a new Subscription ' . sprintf("%03d", $subscription_id),
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Subscription was added successfully');
            return redirect()->back();
        } catch (\Throwable $ex) {
            DB::rollBack();
            Log::error("Error: " . $ex->getMessage());
            toastr()->error('Operation failed');
            return redirect()->back();

        }
    }

    public function editInsuranceSubscription($subscriptionId, Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'product' => 'required',
            'valuation' => 'sometimes|nullable|string',
            'payment_mode' => 'required',
            'period' => 'sometimes|nullable|integer|min:1',
            'penalty' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'grace_period' => 'required|integer|min:1|max:365',
        ]);

        //check  if any payments have been done
        $payed = InsuranceInstallment::where('subscription_id', $subscriptionId)
                ->whereIn('status', [InsuranceInstallment::STATUS_PARTIALLY_PAID, InsuranceInstallment::STATUS_PAID])
                ->count() > 0;

        if ($payed) {
            toastr()->error("Sorry, you can not edit the subscription. Farmer has already done some payment.");
            return redirect()->back();
        }

        $user = Auth::user();
        $product = InsuranceProduct::findOrFail($request->product);
        $subscription = InsuranceSubscriber::findOrFail($subscriptionId);
        try {
            DB::beginTransaction();

            $req = [
                "productId" => $product->id,
                "valuation" => $request->valuation,
                "paymentMode" => $request->payment_mode,
                "period" => $request->period,
                "product" => $product,
                "user" => $user,
                "penalty" => $request->penalty,
                "gracePeriod" => $request->grace_period
            ];

            $this->editSubscription($req, $subscription);

            DB::commit();
            $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Updated a Subscription Policy ' . sprintf("%03d", $subscriptionId),
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Subscription was updated successfully');
            return redirect()->back();
        } catch (\Throwable $ex) {
            DB::rollBack();
            Log::error("Error: " . $ex->getMessage());
            toastr()->error('Operation failed');
            return redirect()->back();
        }
    }

    public function insuranceInstallments($subscription_id)
    {
        $installmentData = $this->installments($subscription_id);
        $installments = $installmentData['installments'];
        $subscription = $installmentData['subscription'];
        $total_wallet_balance = $installmentData['total_wallet_balance'];
        return view('pages.as-farmer.insurance.subscription-installments', compact('installments', 'subscription', 'total_wallet_balance'));
    }


    public function pay_installments(Request $request, $installmentId)
    {
        $installment = InsuranceInstallment::findOrFail($installmentId);
        $max_amount = ceil($installment->amount);
        $this->validate($request, [
            'amount' => "required|integer|min:0|max:$max_amount",
            'source' => 'required'
        ]);

        if ($request->source == InsuranceInstallment::SOURCE_MPESA) {
            toastr()->error('MPESA Payment are not enabled yet');
            return redirect()->back();
        }

        $hasPendingPremiums = InsuranceInstallment::where('subscription_id', $installment->subscription_id)
                ->whereDate('due_date', '<', $installment->due_date)
                ->whereIn('status', [InsuranceInstallment::STATUS_PARTIALLY_PAID, InsuranceInstallment::STATUS_PENDING])
                ->count() > 0;

        if ($hasPendingPremiums) {
            toastr()->error('Please pay previous premium installments first');
            return redirect()->back();
        }
        $user = Auth::user();

        try {
            DB::beginTransaction();
            $this->payInstallment($installment, $request->amount, $user);
            DB::commit();
            $audit_trail_data = [
                'user_id' => $user->id,
                'activity' => "Insurance Policy {$installment->subscription_id} Installment {$installment->id} Payment",
                'cooperative_id' => $user->cooperative->id
            ];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Payment made successfully');
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Error: " . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            toastr()->error('Oops Operation Fail');
            return redirect()->back();
        }

    }

    public function claimLimits()
    {
        $claim_limits = InsuranceClaimLimit::productLimits(Auth::user());
        return view('pages.as-farmer.insurance.claim_limits', compact('claim_limits'));
    }

    public function claims()
    {
        $user = Auth::user();
        $farmerId = $user->farmer->id;
        $claims = DB::select("SELECT c.id AS id, ip.name, d.name AS dependant, c.amount, c.status, c.description,
                                    i.current_limit, c.subscription_id, d.id as dependant_id FROM insurance_claims  c
                                    JOIN insurance_subscribers i ON i.id = c.subscription_id
                                    JOIN insurance_products ip ON i.insurance_product_id = ip.id
                                    LEFT JOIN insurance_dependants d ON i.id = d.subscription_id 
                                    WHERE i.farmer_id = '$farmerId'");

        $details = $this->getSubscriptionDetails($user->farmer->id);
        $subscriptions = $details['subscriptions'];
        $dependants = $details['dependants'];
        return view('pages.as-farmer.insurance.claims', compact('dependants', 'claims', 'subscriptions'));
    }

    public function claim_status_transition($claimId){
        $data = InsuranceClaimStatusTracker::statusTransitions($claimId);
        $transitions = $data['transitions'];
        $claim = $data['claim'];
        return view('pages.as-farmer.insurance.claim_status_transitions', compact('transitions', 'claim'));
    }

    public function addClaim(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'subscription' => 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/|min:1',
            'dependant' => 'sometimes|nullable',
            'description' => 'required'
        ]);

        $user = Auth::user();

        $subscription = InsuranceSubscriber::findOrFail($request->subscription);
        $hasClaims = InsuranceClaim::where('subscription_id', $request->subscription_id)->count() > 0;

        try {
            DB::beginTransaction();
            $this->addInsuranceClaim($subscription, $request->amount, $hasClaims, $user, $request->description, $request->dependant);
            $audit_trail_data = [
                'user_id' => $user->id,
                'activity' => "Submitted claim for subscription id: " . $request->subscription_id,
                'cooperative_id' => $user->cooperative->id
            ];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Claim Created');
            DB::commit();
            return redirect()->back();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Oops operation failed');
            return redirect()->back();
        }
    }

    public function editClaim(Request $request, $claimId): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'subscription' => 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/|min:1',
            'dependant' => 'sometimes|nullable',
            'description' => 'required'
        ]);

        $user = Auth::user();

        $subscription = InsuranceSubscriber::findOrFail($request->subscription);
        $claim = InsuranceClaim::findOrFail($claimId);

        try {
            DB::beginTransaction();

            $this->editInsuranceClaim($request->amount, $claim, $subscription, $user, $request->subscription,
                $request->dependant, $request->description);

            $audit_trail_data = [
                'user_id' => $user->id,
                'activity' => "Submitted claim for subscription id: " . $request->subscription_id,
                'cooperative_id' => $user->cooperative->id
            ];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Claim Created');
            DB::commit();
            return redirect()->back();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Oops operation failed');
            return redirect()->back();
        }
    }

    public function insurance_transaction_history(Request $request)
    {
        $user = Auth::user();
        $subscriptions = InsuranceSubscriber::where('farmer_id', $user->farmer->id)->get();
        $query = $this->transactionHistory($request, $user);
        session()->flashInput($request->input());
        $trxns = $query->orderBy('date', 'desc')->get();
        return view('pages.as-farmer.insurance.transaction_history', compact('trxns', 'subscriptions'));
    }

}
