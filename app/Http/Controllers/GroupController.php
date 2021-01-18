<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
	}
    
    public function index()
    {
        $data = Group::all();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
			'group_name' => 'required|unique:groups'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		]); 
		}

        $input = $request->all(); 
        $input['user_id'] = Auth::id();
        $group = Group::create($input); 
        $data = Group::find($group->id);
		return response()->json([
			'success' => true,
			'data' => $data
		],200); 
    }

    public function show($id)
    {
        return response()->json(Group::find($id));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
			'group_name' => 'required|unique:groups'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		]); 
		}

		$input = $request->all(); 
        $role = Group::where('id', $id)->update($input); 
        $get = Group::find($id);
		return response()->json([
			'success' => true,
			'data' => $get
		],200); 
    }

    public function destroy($id)
    {
        return (Group::find($id)->delete()) 
                ? [ 'response_status' => true, 'message' => 'Group has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'Group cannot delete' ];
    }
    public function group_by_user(){
        $id = Auth::id();
        $group = Group::where('user_id', $id)->get();
        return response()->json($group);
    }
}
