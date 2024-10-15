<?php

namespace App\Http\Controllers;

use App\Events\AuditTrailEvent;
use App\Exports\InsuranceProductClaimsMngtExport;
use App\Exports\InsuranceSubscriptionsExport;
use App\Exports\TransactionHistoryExport;
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
use App\InsuranceTransactionHistory;
use App\InsuranceValuation;
use Auth;
use Carbon\Carbon;
use Excel;
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

    public function benefits()
    {
        $benefits = InsuranceBenefit::benefits(Auth::user()->cooperative_id);
        return view('pages.cooperative.insurance.benefits', compact('benefits'));
    }

    public function addBenefit(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $user = Auth::user();
        $benefit = new InsuranceBenefit();
        $benefit->name = ucwords(strtolower($request->name));
        $benefit->cooperative_id = $user->cooperative_id;
        $benefit->save();
        $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Added an insurance benefit',
            'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Benefit added successfully');
        return redirect()->back();

    }

    public function products()
    {
        $user = Auth::user();
        $benefits = InsuranceBenefit::benefits($user->cooperative_id);
        $products = InsuranceProduct::products($user->cooperative_id);
        return view('pages.cooperative.insurance.products', compact('products', 'benefits'));
    }

    public function addProduct(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'premium' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'interest' => 'sometimes|nullable|regex:/^\d+(\.\d{1,2})?$/',
            'name' => 'required',
            'type' => 'required',
            'benefits' => 'nullable|sometimes'
        ]);

        $user = Auth::user();
        $premium = new InsuranceProduct();
        $premium->name = ucwords(strtolower($request->name));
        $premium->premium = $request->premium;
        $premium->interest = $request->interest ?? 0;
        $premium->cooperative_id = $user->cooperative_id;
        $premium->type = $request->type;
        $premium->save();
        $premium->fresh()->benefits()->attach($request->benefits);
        $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Added an insurance premium',
            'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Product added successfully');
        return redirect()->back();
    }


    public function valuation()
    {
        $user = Auth::user();
        $valuations = InsuranceValuation::where('cooperative_id', $user->cooperative_id)->get();
        $farmers = farmers($user->cooperative_id);
        return view('pages.cooperative.insurance.valuations', compact('valuations', 'farmers'));
    }

    public function addValuation(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'description' => 'required',
            'farmer' => 'required'
        ]);

        $user = Auth::user();
        $valuation = new InsuranceValuation();
        $valuation->type = $request->type;
        $valuation->farmer_id = $request->farmer;
        $valuation->amount = $request->amount;
        $valuation->description = $request->description;
        $valuation->cooperative_id = $user->cooperative_id;
        $valuation->save();

        $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Added a valuation',
            'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Valuation was added successfully');
        return redirect()->back();
    }

    public function getValuationByFarmer($farmerId): \Illuminate\Support\Collection
    {
        return InsuranceValuation::select('id', 'type')->where('farmer_id', $farmerId)->get();
    }

    public function subscriptions()
    {
        $user = Auth::user();
        $cooperative = $user->cooperative->id;
        $farmers = farmers($cooperative);
        $products = DB::select("SELECT id, name from insurance_products where cooperative_id = '$cooperative'");

        $valuations = DB::select("SELECT id, type from insurance_valuations where cooperative_id = '$cooperative'");

        $subscriptions = InsuranceSubscriber::where('cooperative_id', $cooperative)->get();

        return view('pages.cooperative.insurance.subscriptions', compact('valuations', 'products', 'subscriptions', 'farmers'));
    }

    public function newSubscription(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'farmer' => 'required',
            'product' => 'required',
            'valuation' => 'sometimes|nullable|string',
            'payment_mode' => 'required',
            'period' => 'sometimes|nullable|integer|min:1',
            'penalty' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'grace_period' => 'required|integer|min:1|max:365',

        ]);

        // check if already subscribed
        $isSubscribed = InsuranceSubscriber::where('farmer_id', $request->farmer)
                ->where('insurance_product_id', $request->product)->count() > 0;

        if ($isSubscribed) {
            toastr()->error("Farmer is already subscribed to this product");
            return redirect()->back();
        }
        $user = Auth::user();
        $product = InsuranceProduct::findOrFail($request->product);
        try {
            DB::beginTransaction();
            $req = [
                "farmer" => $request->farmer,
                "productId" => $product->id,
                "valuation" => $request->valuation,
                "paymentMode"=>$request->payment_mode,
                "period" =>$request->period,
                "product" => $product,
                "user" => $user,
                "penalty" => $request->penalty,
                "gracePeriod" => $request->grace_period
            ];

            $subscription_id =  $this->addSubscription($req);

            DB::commit();
            $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Added a new Subscription ' . sprintf("%03d", $subscription_id),
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Subscription was added successfully');
            return redirect()->back();
        } catch (\Throwable $ex) {
            DB::rollBack();
            Log::error("Error: " . $ex->getMessage().'| '.$ex->getFile().'| '.$ex->getLine());
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
                "paymentMode"=>$request->payment_mode,
                "period" =>$request->period,
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


    public function configureInstallmentRate()
    {

        $configs = InsurancePaymentModeAdjustedRate::adjustmentRates(Auth::user()->cooperative_id);
        return view('pages.cooperative.insurance.payment_mode_configs', compact('configs'));

    }

    public function addConfigureInstallmentRate(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'payment_mode' => 'required'
        ]);

        $user = Auth::user();
        $isConfigured = InsurancePaymentModeAdjustedRate::where('cooperative_id', $user->cooperative_id)
                ->where('payment_mode', $request->payment_mode)->count() > 0;
        if ($isConfigured) {
            toastr()->warning('Payment mode is already configured');
            return redirect()->back();
        }
        $config = new InsurancePaymentModeAdjustedRate();
        $config->payment_mode = $request->payment_mode;
        $config->adjusted_rate = $request->adjusted_interest ?? 0;
        $config->cooperative_id = $user->cooperative_id;
        $config->save();
        $audit_trail_data = ['user_id' => $user->id,
            'activity' => 'Configured Adjusted rates for payment mode ' . $request->payment_mode,
            'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Payment mode configured successfully');
        return redirect()->back();

    }

    public function updateConfigureInstallmentRate($id, Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'payment_mode' => 'required'
        ]);

        $user = Auth::user();
        $config = InsurancePaymentModeAdjustedRate::findOrFail($id);
        $config->payment_mode = $request->payment_mode;
        $config->adjusted_rate = $request->adjusted_interest ?? 0;
        $config->save();
        $audit_trail_data = ['user_id' => $user->id,
            'activity' => 'Updated  Adjusted rates for payment mode ' . $request->payment_mode,
            'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Payment mode updated successfully');
        return redirect()->back();

    }

    public function insuranceInstallments($subscription_id)
    {
        $installmentData = $this->installments($subscription_id);
        $installments = $installmentData['installments'];
        $subscription = $installmentData['subscription'];
        $total_wallet_balance = $installmentData['total_wallet_balance'];
        return view('pages.cooperative.insurance.subscription-installments', compact('installments', 'subscription', 'total_wallet_balance'));
    }

    public function addInsuranceDependant($subscriptionId, Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'idno' => 'required',
            'dob' => 'required|date',
            'relationship' => 'required',
        ]);

        $user = Auth::user();

        $now = Carbon::now();
        $date = Carbon::parse($request->dob);

        if ($date->gt($now)) {
            toastr()->error('Select a valid date of birth');
            return redirect()->back()->withInput()->withErrors(['dob' => 'Select a valid date of birth']);
        }

        if (InsuranceDependant::RELATIONSHIP_SPOUSE && $now->diffInDays($date) < 18) {
            toastr()->error('Spouse age must greater than 18');
            return redirect()->back()->withInput()->withErrors(['dob' => 'Spouse age must greater than 18']);
        }

        InsuranceDependant::addDependant($request, $subscriptionId, $user);

        $audit_trail_data = ['user_id' => $user->id,
            'activity' => 'Added a dependant to Insurance Policy No. ' . $subscriptionId,
            'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Dependant added successfully');
        return redirect()->back();

    }

    public function dependants($subscriptionId)
    {
        $subscription = InsuranceSubscriber::findOrFail($subscriptionId);
        return view('pages.cooperative.insurance.dependants', compact('subscription'));
    }

    public function editDependants($id, Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'idno' => 'required',
            'dob' => 'required|date',
            'relationship' => 'required',
        ]);

        $user = Auth::user();

        $now = Carbon::now();
        $date = Carbon::parse($request->dob);

        if ($date->gt($now)) {
            toastr()->error('Select a valid date of birth');
            return redirect()->back()->withInput()->withErrors(['dob' => 'Select a valid date of birth']);
        }

        if (InsuranceDependant::RELATIONSHIP_SPOUSE && $now->diffInDays($date) < 18) {
            toastr()->error('Spouse age must greater than 18');
            return redirect()->back()->withInput()->withErrors(['dob' => 'Spouse age must greater than 18']);
        }

        $dependant = InsuranceDependant::findOrFail($id);
        $dependant->cooperative_id = $user->cooperative_id;
        $dependant->name = $request->name;
        $dependant->idno = $request->idno;
        $dependant->relationship = $request->relationship;
        $dependant->dob = $request->dob;
        $dependant->save();

        $audit_trail_data = ['user_id' => $user->id,
            'activity' => 'Updated a dependant to Insurance Policy No. ' . $dependant->subscription_id,
            'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Dependant Updated successfully');
        return redirect()->back();
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
            Log::error("Error: " . $e->getMessage().' '.$e->getFile().' '.$e->getLine());
            toastr()->error('Oops Operation Fail');
            return redirect()->back();
        }

    }


    public function claim_limits()
    {
        $user = Auth::user();
        $products = DB::select("SELECT id, name,premium from insurance_products where cooperative_id = '$user->cooperative_id'");
        $claim_limits = InsuranceClaimLimit::productLimits($user);
        return view('pages.cooperative.insurance.claim_limits', compact('products', 'claim_limits'));
    }

    public function add_claim_limit(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'product' => 'required|unique:insurance_claim_limits,product_id',
            'limit_rate' => 'sometimes|nullable|required_without:amount|regex:/^\d+(\.\d{1,2})?$/|max:100',
            'amount' => 'sometimes|nullable|required_without:limit_rate|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $user = Auth::user();
        $product = InsuranceProduct::findOrFail($request->product);

        if ($request->amount) {
            $amount = $request->amount;
            $rate = (($amount - $product->premium) / $product->premium) * 100;
        } else {
            $amount = (($request->limit_rate / 100) * $product->premium) + $product->premium;
            $rate = $request->limit_rate;
        }

        $claim_limit = new InsuranceClaimLimit();
        $claim_limit->amount = $amount;
        $claim_limit->limit_rate = $rate;
        $claim_limit->product_id = $product->id;
        $claim_limit->cooperative_id = $user->cooperative_id;
        $claim_limit->save();
        $audit_trail_data = [
            'user_id' => $user->id,
            'activity' => "Create a $product->id claim limit",
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Claim added successfully');
        return redirect()->back();
    }

    public function edit_claim_limit(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'product' => 'required',
            'limit_rate' => 'sometimes|nullable|required_without:amount|regex:/^\d+(\.\d{1,2})?$/|max:100',
            'amount' => 'sometimes|nullable|required_without:limit_rate|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $user = Auth::user();
        $product = InsuranceProduct::findOrFail($request->product);

        if ($product->id != $request->product) {
            //check if it already created
            $limit_exists = InsuranceClaimLimit::where('product_id', $request->product)->count() > 0;
            if ($limit_exists) {
                toastr()->error('Product configuration has already been set');
                return redirect()->back();
            }
        }

        if ($request->amount) {
            $amount = $request->amount;
            $rate = round((($amount - $product->premium) / $product->premium) * 100, 2);
        } else {
            $amount = (($request->limit_rate / 100) * $product->premium) + $product->premium;
            $rate = round($request->limit_rate, 2);
        }

        $claim_limit = InsuranceClaimLimit::findOrFail($id);
        $claim_limit->amount = $amount;
        $claim_limit->limit_rate = $rate;
        $claim_limit->product_id = $product->id;
        $claim_limit->save();
        $audit_trail_data = [
            'user_id' => $user->id,
            'activity' => "Update claim $claim_limit->id",
            'cooperative_id' => $user->cooperative->id
        ];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Claim updated successfully');
        return redirect()->back();
    }


    public function claims()
    {
        $user = Auth::user();
        $farmers = farmers($user->cooperative_id);
        $claims = InsuranceClaim::where('cooperative_id', $user->cooperative_id)
            ->orderBy('created_at', 'desc')->get();
        return view('pages.cooperative.insurance.claims', compact('farmers', 'claims'));
    }

    public function getSubscriptionsByFarmer($farmerId): array
    {
        return $this->getSubscriptionDetails($farmerId);
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

    public function updateClaimStatus(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'status' => 'required',
            'comment' => 'required'
        ]);

        $claim = InsuranceClaim::findOrFail($id);
        $user = Auth::user();

        try {
            DB::beginTransaction();
            if ($claim->status != $request->status) {
                //check if it was approved before
                if ($request->status != InsuranceClaim::STATUS_APPROVED) {
                    $notApprovedBefore = InsuranceClaimStatusTracker::where('claim_id', $id)
                            ->where('status', InsuranceClaim::STATUS_APPROVED)->count() == 0;
                    if ($notApprovedBefore) {
                        $claim->subscription->current_limit += $claim->amount;
                        $claim->subscription->save();
                        record_insurance_transaction($claim->subscription->id, $claim->amount, InsuranceTransactionHistory::TYPE_CLAIM, 'Submit a Claim', $user);
                    }
                }

                if ($request->status == InsuranceClaim::STATUS_REJECTED) {
                    $claim->subscription->current_limit -= $claim->amount;
                    $claim->subscription->save();
                    record_insurance_transaction($claim->subscription->id, (-1 * $claim->amount), InsuranceTransactionHistory::TYPE_REJECT_CLAIM, 'Claim rejected', $user);
                }

                $tracker = new InsuranceClaimStatusTracker();
                $tracker->status = $request->status;
                $tracker->comment = $request->comment;
                $tracker->claim_id = $id;

                $previous_status = $claim->status;
                $claim->status = $request->status;
                $claim->updated_at = Carbon::now();
                $claim->save();
                $tracker->save();

                $audit_trail_data = [
                    'user_id' => $user->id,
                    'activity' => "Updated claim $claim->id  from $previous_status to $request->status",
                    'cooperative_id' => $user->cooperative->id
                ];
                event(new AuditTrailEvent($audit_trail_data));
            }
            DB::commit();
            toastr()->success('Status updated successful');
            return redirect()->back();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Error: " . $e->getMessage());
            toastr()->error('Oops Operation failed');
            return redirect()->back();
        }
    }

    public function claim_status_transition($claimId)
    {
        $data = InsuranceClaimStatusTracker::statusTransitions($claimId);
        $transitions = $data['transitions'];
        $claim = $data['claim'];
        return view('pages.cooperative.insurance.claim_status_transitions', compact('transitions', 'claim'));
    }

    public function insurance_transaction_history(Request $request)
    {
        $user = Auth::user();
        $farmers = farmers($user->cooperative_id);
        $query = $this->transactionHistory($request, $user);
        session()->flashInput($request->input());
        $trxns = $query->orderBy('date', 'desc')->limit(100)->get();
        return view('pages.cooperative.insurance.transaction_history', compact('trxns', 'farmers'));
    }

    public function export_insurance_subscriptions($type)
    {
        $cooperative = Auth::user()->cooperative;
        if ($type != env('PDF_FORMAT')) {
            $file_name = 'insurance_subscriptions_report_' . date('d_m_Y') . '.' . $type;
            return Excel::download(new InsuranceSubscriptionsExport($cooperative->id), $file_name);
        } else {
            $data = [
                'title' => 'Insurance Subscriptions',
                'pdf_view' => 'insurancesubscriptions',
                'records' => InsuranceSubscriber::where('cooperative_id', $cooperative->id)->get(),
                'filename' => 'insurance_subscriptions_report_' . date('d_m_Y'),
                'orientation' => 'landscape'
            ];
            return deprecated_download_pdf($data);
        }
    }

    public function export_insurance_transaction_history($type)
    {
        $cooperative = Auth::user()->cooperative;
        if ($type != env('PDF_FORMAT')) {
            $file_name = 'insurance_transaction_report_' . date('d_m_Y') . '.' . $type;
            return Excel::download(new TransactionHistoryExport($cooperative->id), $file_name);
        } else {
            $data = [
                'title' => 'Transaction History Reports',
                'pdf_view' => 'transactionhistory',
                'records' => InsuranceTransactionHistory::where('cooperative_id', $cooperative->id)->get(),
                'filename' => 'insurance_transaction_report_' . date('d_m_Y'),
                'orientation' => 'landscape'
            ];
            return deprecated_download_pdf($data);
        }
    }

    public function export_insurance_claims_mngt($type)
    {
        $cooperative = Auth::user()->cooperative;
        if ($type != env('PDF_FORMAT')) {
            $file_name = 'insurance_claims_mngt_' . date('d_m_Y') . '.' . $type;
            return Excel::download(new InsuranceProductClaimsMngtExport($cooperative->id), $file_name);
        } else {
            $data = [
                'title' => 'Insurance Product Claims Management',
                'pdf_view' => 'insurance_claims_mngt',
                'records' => InsuranceClaim::where('cooperative_id', $cooperative->id)
                ->orderBy('created_at', 'desc')->get(),
                'filename' => 'insurance_claims_mngt_' . date('d_m_Y'),
                'orientation' => 'landscape'
            ];
            return deprecated_download_pdf($data);
        }
    }
}
