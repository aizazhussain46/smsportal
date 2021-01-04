<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campaign;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
class CampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
	}
    
    public function index()
    {
        $data = Campaign::all();
        foreach($data as $camp){
            $camp['user'] = User::find($camp->user_id);
        }
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
			'campaign_name' => 'required'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		]); 
		}

        $input = $request->all(); 
        $input['user_id'] = Auth::id();
		$campaign = Campaign::create($input); 
		return response()->json([
			'success' => true,
			'data' => $campaign
		],200); 
    }

    public function show($id)
    {
        return response()->json(Campaign::find($id));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
			'campaign_name' => 'required'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		]); 
		}

		$input = $request->all(); 
        $role = Campaign::where('id', $id)->update($input); 
        $get = Campaign::find($id);
		return response()->json([
			'success' => true,
			'data' => $get
		],200); 
    }

    public function destroy($id)
    {
        return (Campaign::find($id)->delete()) 
                ? [ 'response_status' => true, 'message' => 'Campaign has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'Campaign cannot delete' ];
    }
}
