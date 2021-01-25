<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Contact;
use App\Campaign;
use App\Mask;
use App\Bulksmsdata as Bulk;
use Validator;
class BulkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
	}
    public function bulk_sms(Request $request){
        $validator = Validator::make($request->all(), [ 
            'campaign_id' => 'required',
            'group_id' => 'required',
            'masking_id' => 'required',
            'message' => 'required'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		]); 
		}
        $camp_id = $request->campaign_id;
        $group_id = $request->group_id;
        $message = $request->message;
        $masking_id = $request->masking_id;

        $campaign = Campaign::find($camp_id);
        $mask = Mask::find($masking_id);
        $contact = Contact::where('group_id',$group_id)->get();
        $numbers = '';
        foreach($contact as $num){
            $numbers .= $num->number.',';
        }
        
        $fields = array('campaign'=>$campaign->campaign_name, 'message'=>$message,'numbers'=>$numbers, 'masking'=>$mask->mask_name);
        // var_dump($fields);
        // die;
        $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => url('api/send_bulk_sms'),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $fields,
));
$response = curl_exec($curl);
curl_close($curl);
$res = json_decode($response);
$insert = Bulk::create(['user_id'=>Auth::id(),'campaign_id'=>$camp_id,'group_id'=>$group_id,'message'=>$message,'response'=>$response]);
        return response()->json([
            'success' => true,
            'data' => $res
        ],200);

    }
}
