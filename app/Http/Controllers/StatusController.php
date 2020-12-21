<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Status;
use App\District;
use Illuminate\Support\Facades\Auth;
use Validator;
class StatusController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:api');
	}
 
    public function index()
    {
        return Status::all();
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 'status' => 'required' ]); 
		if ($validator->fails()) { 

			return response()->json([ 'success' => false, 'errors' => $validator->errors() ]); 
		}
        
        $arr = [ 'status' => $request->status , 'keyword' => $request->keyword ];

        $created = Status::create($arr); 

        if ($created) {
            return response()->json(['success' => true,'data' => $created],200);
        } else {
            return response()->json(['success' => false,'data' => ''],200);
        }       
		
    }


    public function show($id)
    {
        return Status::find($id);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 'status' => 'required' ]); 

		if ($validator->fails()) { 
			return response()->json([ 'success' => false, 'errors' => $validator->errors() ]); 
        }
        
        $arr = [ 'status' => $request->status , 'keyword' => $request->keyword ];

        $updated = Status::where('id', $id)->update($arr); 
        if ($updated) {
            return response()->json([ 'success' => true, 'data' => Status::find($id) ],200);
        } else {
            return response()->json([ 'success' => false, 'data' => '' ],200);
        }
        
		
    }


    public function destroy($id)
    {
        return (Status::find($id)->delete()) 
                ? [ 'response_status' => true, 'message' => 'status has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'status cannot delete' ];
    }
    
}
