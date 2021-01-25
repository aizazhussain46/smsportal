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
        $this->middleware('auth:api')->except('bulk_sms');
	}
    public function quick_sms(Request $request){
        $destination = $request->number;
        $message = $request->message;
        $masking = $request->masking;

$url = 'http://cbs.zong.com.pk/reachcwsv2/corporatesms.svc?wsdl';
$client = new SoapClient($url, array("trace" => 1, "exception" => 0));

//Sending Quick SMS
$resultQuick = $client->QuickSMS(
    array(
        'obj_QuickSMS'=>array(
            'loginId'=>'923172332451', 
            'loginPassword'=>'Zong@123', 
            'Destination'=>$destination, 
            'Mask'=>$masking, 
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
    public function bulk_sms(Request $request){
        $campaign = $request->campaign;
        $numbers = $request->numbers;
        $message = $request->message;
        $masking = $request->masking;

        date_default_timezone_set('Asia/karachi');
        ini_set("soap.wsdl_cache_enabled", 0);
        $url         = 'http://cbs.zong.com.pk/ReachCWSv2/CorporateSmsV2.svc?wsdl';
        $client     = new SoapClient($url, array("trace" => 1, "exception" => 0)); 
        
        $resultBulkSMS = $client->BulkSmsv2(
                        array('objBulkSms' => 
                                        array(	'LoginId'=>  '923172332451', //here type your account name
                                                'LoginPassword'=>'Zong@123', //here type your password
                                                'Mask'=>$masking, //here set allowed mask against your account or you will get invalid mask
                                                'Message'=>$message,
                                                'UniCode'=>'0',
                                                'CampaignName'=>$campaign, // Any random name or type uniqid() to generate random number, you can also specify campaign name here if want to send no to any existing campaign, numberCSV parameter will be ignored
                                                'CampaignDate'=>date('m/d/Y h:m:s a'), // data from where sms will start sending, if not sure type current date in m/d/y hh:mm:ss tt format.
                                                'ShortCodePrefered'=>'n',
                                                'NumberCsv'=>$numbers
                                                //'NumberCsv'=>'923101807690,923443817338'
                                            )));
        echo json_encode($resultBulkSMS);
    }

    public function quick_sms_logs(){
        $user = Auth::user();
        if($user->master == 1){
            $logs = Smsdata::all();
        }
        else{
            $logs = Smsdata::where('user_id', $user->id)->get();
        }

        return response()->json([
			'success' => true,
			'data' => $logs
		],200);
    }
}
