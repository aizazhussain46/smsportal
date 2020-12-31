<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Smsdata;
use SoapClient;

class ApiController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
	// }
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

        $res = json_decode($response);

        $insert = Smsdata::create(['user_id'=>Auth::id(),'recipient'=>$destination,'message'=>$message,'response'=>$response]);
        return response()->json([
            'success' => true,
            'data' => $res
        ],200);
    }
    public function account_summary(Request $request){
        
        $url = "http://cbs.zong.com.pk/reachcwsv2/corporatesms.svc?wsdl" ;
        $client = new SoapClient($url , array('trace'=>1 , 'exception'=>0));
        $result = $client->GetAccountSummary(array('obj_GetAccountSummary'=>array('loginId'=>'923172332451' , 'loginPassword'=>'Zong@123')));
        //print_r($result->GetAccountSummaryResult->CounterResponse);
        return json_encode($result);
        //print_r($result);
    }
}
