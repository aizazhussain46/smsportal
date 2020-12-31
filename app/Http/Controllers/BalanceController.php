<?php

namespace App\Http\Controllers;

use App\Balance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
class BalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
	}
    public function total_balance(){        
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => url('api/smsaccountsummary'),
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
        $res = json_decode($response);
        $result = $res->GetAccountSummaryResult->CounterResponse;
        //print_r($result);
        return response()->json([
			'success' => true,
			'balance' => $result
		],200);
    }
    public function loggedin_client_balance(){ 
            $user_id = Auth::id();
            $balance = Balance::where('user_id', $user_id)->select('total_sms')->first();
            return response()->json([
                'success' => true,
                'balance' => $balance
            ],200);
    }
}
