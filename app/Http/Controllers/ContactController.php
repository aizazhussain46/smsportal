<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
	}
    
    public function index()
    {
        $data = Contact::all();
        foreach($data as $camp){
            $camp['user'] = User::find($camp->user_id);
        }
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'campaign_id' => 'required',
            'number' => 'required',
            'name' => 'required',
            'age' => 'required',
            'area' => 'required',
            'city_id' => 'required',
            'gender' => 'required'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		]); 
		}

        $input = $request->all(); 
        $input['user_id'] = Auth::id();
		$contact = Contact::create($input); 
		return response()->json([
			'success' => true,
			'data' => $contact
		],200); 
    }

    public function show($id)
    {
        return response()->json(Contact::find($id));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
			'campaign_id' => 'required',
            'number' => 'required',
            'name' => 'required',
            'age' => 'required',
            'area' => 'required',
            'city_id' => 'required',
            'gender' => 'required'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		]); 
		}

		$input = $request->all(); 
        $role = Contact::where('id', $id)->update($input); 
        $get = Contact::find($id);
		return response()->json([
			'success' => true,
			'data' => $get
		],200); 
    }

    public function destroy($id)
    {
        return (Contact::find($id)->delete()) 
                ? [ 'response_status' => true, 'message' => 'Contact has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'Contact cannot delete' ];
    }
}
