<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Smsdata;
use SoapClient;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
	}
    public function quick_sms(Request $request){
        $destination = $request->number;
        $message = $request->message;

$url = 'http://cbs.zong.com.pk/reachcwsv2/corporatesms.svc?wsdl';
$client = new SoapClient($url, array("trace" => 1, "exception" => 0));

//Sending Quick SMS
$resultQuick = $client->QuickSMS(
    array(
        'obj_QuickSMS'=>array(
            'loginId'=>'923172332451', 
            'loginPassword'=>'Zong@123', 
            'Destination'=>$destination, 
            'Mask'=>'Sindh TBCP', 
            'Message'=>$message, 
            'UniCode'=>0 , 
            'ShortCodePrefered'=>'n')));

$response = json_encode($resultQuick);

        // $recipient = $request->number;
        // $message = $request->message;
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        // CURLOPT_URL => "http://localhost/Php_Apis/quick_sms/SMSapi.php",
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => "",
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => "POST",
        // CURLOPT_POSTFIELDS => array('destination' => $recipient,'message' => $message),
        // ));
        // $response = curl_exec($curl);
        // curl_close($curl);
        $res = json_decode($response);

        $insert = Smsdata::create(['user_id'=>Auth::id(),'recipient'=>$destination,'message'=>$message,'response'=>$response]);
        return response()->json([
            'success' => true,
            'data' => $res
        ],200);
    }
    public function account_summary(Request $request){
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://localhost/Php_Apis/quick_sms/sms_Quick_Summary.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
