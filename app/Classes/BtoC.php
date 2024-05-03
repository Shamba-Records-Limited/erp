<?php
namespace Apps\Classes;
use Illuminate\Http\Request;

class BtoC
{
    //b2c
    /**
     * @param $InitiatorName | 	This is the credential/username used to authenticate the transaction request.
    * @param $SecurityCredential | Encrypted password for the initiator to autheticate the transaction request
    * @param $CommandID | Unique command for each transaction type e.g. SalaryPayment, BusinessPayment, PromotionPayment
    * @param $Amount | The amount being transacted
    * @param $PartyA | Organization’s shortcode initiating the transaction.
    * @param $PartyB | Phone number receiving the transaction
    * @param $Remarks | Comments that are sent along with the transaction.
    * @param $QueueTimeOutURL | The timeout end-point that receives a timeout response.
    * @param $ResultURL | The end-point that receives the response of the transaction
    * @param $Occasion | 	Optional
    * @return string
    */
    public static function b2c($Amount, $PartyB){
        $InitiatorName = '';
        $initiatorpassword = '';
        $shortcode = '';

        $SecurityCredential=$this->getEncryption($shortcode,$initiatorpassword);
        $CommandID="SalaryPayment";
        $Amount=$amount;
        $PartyA=$shortcode;
        $PartyB=$phone;
        $Remarks="Customer Transfer";

        $environment = 'sandbox';
        $QueueTimeOutURL = env('APP_URL').'/api/chama_member/b2c/queue';
        $ResultURL = env('APP_URL').'/api/chama_member/b2c/result';

        if( $environment =="live"){
            $url = 'https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
            $token=self::generateLiveToken();
        }elseif ($environment=="sandbox"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
            $token=self::generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }
        Log::info('Disbursing '.$Amount.' to '.$PartyB);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token));

        $curl_post_data = array(
            'InitiatorName' => $InitiatorName,
            'SecurityCredential' => $SecurityCredential,
            'CommandID' => $CommandID ,
            'Amount' => $Amount,
            'PartyA' => $PartyA ,
            'PartyB' => $PartyB,
            'Remarks' => $Remarks,
            'QueueTimeOutURL' => $QueueTimeOutURL,
            'ResultURL' => $ResultURL,
            'Occasion' => $Occasion
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);
        Log::info('curl_response::');
        Log::info($curl_response);
        return json_encode($curl_response);
    }
    //get security credential
    public function getEncryption($shortcode,$initiatorpassword){
        $pubkey= File::get(storage_path('app/ProductionCertificate.cer'));
        openssl_public_encrypt($initiatorpassword, $output, $pubkey, OPENSSL_PKCS1_PADDING);
        $credential = base64_encode($output);
        
        return $credential;
    }
    /**
     * use this function to generate a sandbox token
    * @return mixed
    */
    public static function generateLiveToken(){
        if(!isset($consumer_key)||!isset($consumer_secret)){
            Log::info("please declare the consumer key and consumer secret as defined in the documentation");
        }

        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $credentials = base64_encode($consumer_key.':'.$consumer_secret);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
        curl_setopt($curl, CURLOPT_HEADER,false);
        curl_setopt($curl, CURLOPT_NOBODY,false);    // dont need body
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $curl_response = curl_exec($curl);
        $token = json_decode($curl_response)->access_token;
        Log::info("TOKEN::".$token);
        return $token;
        $httpCode = curl_getinfo($curl , CURLINFO_HTTP_CODE); // this results 0 every time
        Log::info($httpCode);
        if($curl_response === false){
            $response = curl_error($curl);
        }
        
        $access_token=json_decode($curl_response);
        Log::info($access_token->access_token);
        return $access_token->access_token;
    }
    //callback
    public function b2cResult(Request $request) {
        sleep(10);
        Log::info("RESULT");
        $result = $request->Result;
        Log::info($result);
        $conv_id = $result['ConversationID'];
        $originator_conv_id = $result['OriginatorConversationID'];
        $result_desc = $result['ResultDesc'];
        
        try{
            return 'success';
        }
        catch(Throwable $t) {
            DB::rollback();
            Log::error($t);
            return response()->json('Error',500);
        }
        
    }

    //queue
    public function b2cQueue(Request $request) {
        Log::info('QUEUE');
        Log::info($request);
    }
    //register c2b urls --added to the class
    public static function registerC2BUrls($ShortCode,$ResponseType,$ConfirmationURL,$ValidationURL){
            $environment = env("MPESA_ENV");
            $environment = 'sandbox';

            if( $environment =="live"){
                $token=self::generateLiveToken();
                $url = 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl'; //live
            }elseif ($environment=="sandbox"){
                $token=self::generateSandBoxToken();
                $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl'; //sandbox
            }else{
                return json_encode(["Message"=>"invalid application status"]);
            }
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token)); //setting custom header
    
            $curl_post_data = array(
            //Fill in the request parameters with valid values
                'ShortCode' => $ShortCode,
                'ResponseType' => $ResponseType,
                'ConfirmationURL' =>$ConfirmationURL,
                'ValidationURL' => $ValidationURL
            );
        
            $data_string = json_encode($curl_post_data);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

            $curl_response = curl_exec($curl);
            return json_encode($curl_response);

    }

}
