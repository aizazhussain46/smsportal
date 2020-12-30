<?php

namespace App\Http\Controllers;
use App\Topup;
use App\Balance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
class TopupController extends Controller
{
    public function index()
    {
        $user = Balance::all();
        return response()->json([
			'success' => true,
			'data' => $user
		],200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id' => 'required',
            'total_sms' => 'required'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		]); 
        }

        $user_id = $request->user_id;
        $total_sms = $request->total_sms;
        $expiry_date = date('Y-m-d', strtotime($request->expiry_date));

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

        if($total_sms > $result->Total_Balance)
        {
            return response()->json([
                'msg' => 'Account limit exceeds.'
            ]);
        }

        $fields = array(
            'user_id' => $user_id,
            'total_sms' => $total_sms,
            'expiry_date' => $expiry_date
        );
        $topup = Topup::create($fields);
        $check = Balance::where('user_id',$user_id)->first();
        if($check){
            if($check->expiry_date >= date('Y-m-d')){
                $total = $check->total_sms+$total_sms;
            }
            else{
                $total = $total_sms;
            }
            $balance = Balance::where('user_id',$user_id)->update(['total_sms'=>$total, 'expiry_date'=>$expiry_date]);
        }
        else{
            $balance = Balance::create($fields);
        }
        $user = Balance::where('user_id',$user_id)->first();
        return response()->json([
			'success' => true,
			'data' => $user
		],200);
    }

    public function show(Topup $topup)
    {
        //
    }

    public function update(Request $request, Topup $topup)
    {
        //
    }

    public function destroy(Topup $topup)
    {
        //
    }
}
