<?php

namespace App\Http\Traits;

use App\CooperativePaymentConfigs;
use App\Customer;
use App\LNMTransaction;
use App\User;
use Carbon\Carbon;
use Log;

trait Payment
{
    private function lnmPassword($paybill, $pass_key): string
    {
        Log::debug("generate LNM password");
        $lipa_time = Carbon::rawParse('now')->format('YmdHms');
        $passkey = $pass_key;
        $BusinessShortCode = $paybill;
        $timestamp = $lipa_time;
        return base64_encode($BusinessShortCode . $passkey . $timestamp);
    }

    private function stk_push(string $phone, string $amount, $reference, $model, $id, $farmer, $customer=null,$isFarmer=true)
    {
        $coop = $isFarmer ? $farmer->user->cooperative_id : $customer->cooperative_id;
        $setting = CooperativePaymentConfigs::where('cooperative_id', $coop)->where('type', 'c2b')->first();
        if (!$setting) {
            $setting = CooperativePaymentConfigs::where('type', 'c2b')->first();
        }

        $shortcode = $setting->shortcode;
        $consumer_key = $setting->consumer_key;
        $consumer_secret = $setting->consumer_secret;
        $passkey = $setting->passkey;

        $paybill = $shortcode;
        $phone = $phone == null ? ( $isFarmer ? $farmer->phone_no : $customer->phone_number) : $phone;
        $phone = "254" . substr($phone, -9);

        $url = env('LNM_STKPUSH_ENDPOINT');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . generateAccessToken($consumer_key, $consumer_secret, env('SET_CREDENTIALS_URL'))));
        $curl_post_data = [
            'BusinessShortCode' => $paybill,
            'Password' => $this->lnmPassword($paybill, $passkey),
            'Timestamp' => Carbon::rawParse('now')->format('YmdHms'),
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => $paybill,
            'PhoneNumber' => $phone,
            'CallBackURL' => env('APP_URL') . '/api/c2b/stkpush-result',
            'AccountReference' => $id,
            'TransactionDesc' => $reference
        ];

        Log::debug("STK PUSH Payload ");
        Log::debug(print_r($curl_post_data, true));
        $data_string = json_encode($curl_post_data,JSON_UNESCAPED_SLASHES);
        Log::debug($data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = json_decode(curl_exec($curl));
        Log::info("**********************************");
        Log::info(json_encode($curl_response));
        Log::info("**********************************");
        Log::info("Model: $model Id: $id");
        $model_obj = $model::find($id);
        $model_obj->merchant_request_id = $curl_response->MerchantRequestID;
        $model_obj->checkout_request_id = $curl_response->CheckoutRequestID;
        $model_obj->save();
        Log::info($model_obj);

        $lnmTrx = new LNMTransaction();
        $lnmTrx->merchant_request_id = $curl_response->MerchantRequestID;
        $lnmTrx->checkout_request_id = $curl_response->CheckoutRequestID;
        $lnmTrx->result_code = $curl_response->ResponseCode;
        $lnmTrx->result_description = $curl_response->ResponseDescription;
        $lnmTrx->amount = $amount;
        $lnmTrx->phone_number = $phone;
        $lnmTrx->status = LNMTransaction::STATUS_INITIATED;
        $lnmTrx->farmer_id = $isFarmer ? $farmer->id : null;
        $lnmTrx->customer_id = $isFarmer ? null : $customer->id;
        $lnmTrx->cooperative_id = $isFarmer ? $farmer->user->cooperative_id : $customer->cooperative_id;
        $lnmTrx->model_name = $model;
        $lnmTrx->save();
        if ($curl_response->ResponseCode == "0") {
            Log::info("STK push sent");
        } else {
            Log::error("STK push failed:[{$curl_response->ResponseDescription}]");
        }
    }

}
