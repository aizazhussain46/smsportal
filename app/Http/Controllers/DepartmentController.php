<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use Illuminate\Support\Facades\Auth;
use Validator;
class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
	}
    public function index()
    {
        return Department::orderBy('id','DESC')->get();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 'department' => 'required' ]); 
		if ($validator->fails()) { 

			return response()->json([ 'success' => false, 'errors' => $validator->errors() ]); 
        }
        
        $arr = [ 'department' => $request->department ];

        $created = Department::create($arr);
        if ($created) {
            return response()->json([ 'success' => true, 'data' => $created ],200); 
        } else {
            return response()->json([ 'success' => false, 'data' => '' ],200); 
        }
		
    }

    public function show($id)
    {
        return Department::find($id);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 'department' => 'required' ]); 

		if ($validator->fails()) { 

			return response()->json([ 'success' => false, 'errors' => $validator->errors() ]); 
        }
        $arr = [ 'department' => $request->department ];

        $updated = Department::where('id', $id)->update($arr); 

        if ($updated) {
            $department = Department::find($id);

            return response()->json([ 'success' => true, 'data' => $department ],200); 

        } else {
            return response()->json([ 'success' => true, 'data' => '' ],200); 
        }
    }

    public function destroy($id)
    {
        return (Department::find($id)->delete()) 
                ? [ 'response_status' => true, 'message' => 'Department has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'Department cannot delete' ];
    }

    public function departments_except_btl()
    {
        return Department::orderBy('id','DESC')->whereNotIn('id', array(5))->get();
    }
}
