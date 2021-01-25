<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mask;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
class MaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
	}
    
    public function index()
    {
        $data = Mask::all();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'mask_name' => 'required|unique:masks',
            'user_id' => 'required'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		]); 
		}

        $input = $request->all(); 
        $input['user_id'] = $request->user_id;
        $mask = Mask::create($input); 
        $data = Mask::find($mask->id);
		return response()->json([
			'success' => true,
			'data' => $data
		],200); 
    }

    public function show($id)
    {
        return response()->json(Mask::find($id));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
            'mask_name' => 'required|unique:masks',
            'user_id' => 'required'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		]); 
		}

		$input = $request->all(); 
        $mask = Mask::where('id', $id)->update($input); 
        $get = Mask::find($id);
		return response()->json([
			'success' => true,
			'data' => $get
		],200); 
    }

    public function destroy($id)
    {
        return (Mask::find($id)->delete()) 
                ? [ 'response_status' => true, 'message' => 'Mask has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'Mask cannot delete' ];
    }
    public function mask_by_user(){
        $id = Auth::id();
        $mask = Mask::where('user_id', $id)->get();
        return response()->json($mask);
    }
}
