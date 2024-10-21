<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Log;
use File;
use Auth;
use App\Wallet;
use App\WalletTransaction;
use App\Payment;
use App\CooperativePaymentConfigs;

class B2CController extends Controller
{
    //b2c
    /**
     * @param $Amount | The amount being transacted
     * @param $PartyB | Phone number receiving the transaction
     * @param $Remarks | Comments that are sent along with the transaction.
     * @param $user User user object
     * @return string
     * @throws FileNotFoundException
     */
    public static function b2c($Amount, $PartyB, $Remarks, User $user)
    {
        $coop = $user->cooperative->id;
        //get configs
        $setting = CooperativePaymentConfigs::where('cooperative_id', $coop)->where('type', 'b2c')->first();
        if (!$setting) {
            $setting = CooperativePaymentConfigs::where('type', 'b2c')->first();
        }
        Log::info($setting->cooperative->name . " settings: ", $setting);
        $shortcode = $setting->shortcode;
        $initiator_name = $setting->initiator_name;
        $initiator_pass = $setting->initiator_pass;

        $SecurityCredential = self::getEncryption($initiator_pass);
        $CommandID = "SalaryPayment";
        $PartyA = $shortcode;
        $Remarks = $Remarks ?? "Customer Transfer";
        $Occasion = 'Payments to farmer';
        $QueueTimeOutURL = env('APP_URL') . '/api/b2c/queue';
        $ResultURL = env('APP_URL') . '/api/b2c/result';
        $url = env('B2C_ENDPOINT');
        $token = generateAccessToken($setting->consumer_key, $setting->consumer_secret, env('SET_CREDENTIALS_URL'));
        Log::info('Disbursing ' . $Amount . ' to ' . $PartyB);
        Log::info("B2C URL: $url\nQueueTimeOutURL: $QueueTimeOutURL\nResultURL $ResultURL");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $token));

        $curl_post_data = array(
            'InitiatorName' => $initiator_name,
            'SecurityCredential' => $SecurityCredential,
            'CommandID' => $CommandID,
            'Amount' => $Amount,
            'PartyA' => $PartyA,
            'PartyB' => $PartyB,
            'Remarks' => $Remarks,
            'QueueTimeOutURL' => $QueueTimeOutURL,
            'ResultURL' => $ResultURL,
            'Occasion' => $Occasion
        );

        Log::debug("B2c Post Data ", print_r($curl_post_data, true));
        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);

        if (curl_errno($curl)) {
            Log::info(curl_error($curl));
        }
        Log::info('curl_response::');
        Log::info($curl_response);
        Log::info(curl_getinfo($curl, CURLINFO_HTTP_CODE));
        return json_encode($curl_response);
    }
    //get security credential

    /**
     * @throws FileNotFoundException
     */
    public static function getEncryption($initiator_pass): ?string
    {
        try {
            $pubkey = File::get(storage_path('app/SandboxCertificate.cer'));
            openssl_public_encrypt($initiator_pass, $output, $pubkey, OPENSSL_PKCS1_PADDING);
            return base64_encode($output);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return null;
        }

    }

    //callback
    public function b2cResult(Request $request)
    {
        Log::info("RESULT", $request->all());
        $result = $request->Result;
        Log::info($result);
        $conv_id = $result['ConversationID'];
        $originator_conv_id = $result['OriginatorConversationID'];
        $result_desc = $result['ResultDesc'];
        //update
        try {
            $result_code = $result['ResultCode'];

            $transaction = WalletTransaction::where('conv_id', $conv_id)->where('orig_conv_id', $originator_conv_id)->first();
            if ($transaction) {
                $transaction->reference = $result['TransactionID'];
                $transaction->description = json_encode($result);
                if ($result_code <= 0) {
                    $transaction->status = 1;
                    //update wallet
                    $wallet = Wallet::find($transaction->wallet_id);
                    $wallet->available_balance -= $transaction->amount;
                    $wallet->save();
                } else {
                    $transaction->status = 0;
                }
                $transaction->save();
            }
            return 'success';
        } catch (Throwable $t) {
            DB::rollback();
            Log::error($t->getMessage());
            Log::debug($t->getTraceAsString());
            return response()->json('Error', 500);
        }

    }

    //queue
    public function b2cQueue(Request $request)
    {
        Log::info('QUEUE ', $request->all());
    }

    //register c2b urls --added to the class
    public static function registerC2BUrls($ShortCode, $ResponseType, $ConfirmationURL, $ValidationURL)
    {
        $user = Auth::user();

        $setting = CooperativePaymentConfigs::where('cooperative_id', $user->cooperative_id)->where('type', 'b2c')->first();
        if ($setting == null) {
            $setting = CooperativePaymentConfigs::where('type', 'b2c')->first();
        }
        $url = env('REGISTER_URL');
        $token = generateAccessToken($setting->consumer_key, $setting->consumer_secret, env('SET_CREDENTIALS_URL'));
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $token)); //setting custom header

        $curl_post_data = array(
            //Fill in the request parameters with valid values
            'ShortCode' => $ShortCode,
            'ResponseType' => $ResponseType,
            'ConfirmationURL' => $ConfirmationURL,
            'ValidationURL' => $ValidationURL
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);
        curl_close($curl);
        return json_encode($curl_response);

    }
}
