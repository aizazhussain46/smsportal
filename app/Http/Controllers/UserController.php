<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;

class UserController extends Controller
{
public function __construct()
{
$this->middleware('auth:api');
}
public function index()
{
$users = User::orderBy('id','DESC')->get();
return response()->json([ 'success' => true,'data' => $users] ,200);
}


public function store(Request $request)
{

$validator = Validator::make($request->all(), [ 
'role_id' => 'required',
'name' => 'required|alpha', 
'department_id' => 'required',
'email' => 'required|email|unique:users',
'password' => 'required', 
'confirm_password' => 'required|same:password',
'mobile_no' => 'required|numeric|unique:users',
'cnic' => 'required|numeric|unique:users',
]);

if ($validator->fails()) { 
return response()->json([ 
'success' => false, 
'errors' => $validator->errors() 
]); 
}

$arr = $this->fields($request);
$arr['password'] = bcrypt($request->password);
$created = User::create($arr);
list($status,$data) = $created ? [ true , User::find($created->id) ] : [ false , ''];
return ['success' => $status,'data' => $data];

}


public function update(Request $request, $id)
{

$validator = Validator::make($request->all(), [ 
'role_id' => 'required',
'name' => 'required|alpha', 
'department_id' => 'required',
'email' => 'required|email',
'mobile_no' => 'required|numeric',
'cnic' => 'required|numeric',

]); 

if ($validator->fails()) { 
return [ 'success' => false, 'errors' => $validator->errors() ]; 
}

$arr = $this->fields($request);    
$updated = User::where('id',$id)->update($arr);
list($status,$data) = $updated ? [ true , User::find($id) ] : [ false , ''];
return ['success' => $status,'data' => $data];
}

public function change_password(Request $request,$id)
{
$validator = Validator::make($request->all(), [ 
'change_password' => 'required'
]); 
if ($validator->fails()) { 
return response()->json([ 'success' => false, 'errors' => $validator->errors() ]); 
}
$response = false;
$update = User::where('id', $id)->update(['password' => bcrypt($request->change_password)]);
$response = ($update) ?  true : false ;
return response()->json(['success' => $response] );
}

public function admins()
{
$data = User::where('master',1)->where('id','!=',1)->get();
return response()->json(['success' => true,'data' => $data]);
}

public function user_2be_assigned($id,$master,$role_id)
{
$data = [];

if($master){
$data = User::where('id','!=',$id)->where('master',1)
->where('role_id',1)
->where('role_id',2)
->where('role_id',3)
->where('role_id',4)
->get();
}
else{
if($role_id == 1){
$data = User::where('master',0)
->where('role_id','!=',1)
->where('role_id',2)
->orWhere('role_id',3)
->orWhere('role_id',4)
->get();
}
else if($role_id == 2){
$data = User::where('id','!=',$id)->where('role_id',2)->orWhere('role_id',3)->orWhere('role_id',4)->get();
}
else if($role_id == 3){
$data = User::where('role_id',4)->get();
}
}

return response()->json(['success' => true,'data' => $data]);

} 

public function destroy($id)
{
return (User::find($id)->delete()) 
? [ 'response_status' => true, 'message' => 'user has been deleted' ] 
: [ 'response_status' => false, 'message' => 'user cannot delete' ];
}

public function created_by_users(){
$data = User::whereIn('role_id',[1,2])->get();
return response()->json(['success' => true,'data' => $data]);
}
public function assigned_to_users(){
$data = User::whereIn('role_id',[3,4])->get();
return response()->json(['success' => true,'data' => $data]);
}


public function fields($request)
{
# code...
return [
'role_id' => $request->role_id, 
'department_id' => $request->department_id, 
'master' => 0, 
'email' => $request->email, 
'name' => $request->name,   
'mobile_no' => $request->mobile_no, 
'cnic' =>  $request->cnic, 
'isActive' => $request->isActive, 
'district_id' => $request->district_id
];
}
}
