<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Validator;
class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('login','btl_login');
	}

	public function login(Request $request){ 

	 return $this->login_worker([
		 'email' => $request->email,
		 'password' => $request->password,
		 'isActive' => 1
		 ]);

	}


	public function btl_login(Request $request){ 
		  
		return $this->login_worker([
			'email' => $request->email, 
			'password' => $request->password, 
			'isActive' => 1,
			'department_id' => 5
			]);
	
		}

	public function me(Request $request){
		return response()->json([ 'user' => Auth::user() ],200); 
	}

	public function login_worker($params)
	{
		if(Auth::attempt($params)){ 
			$user = Auth::user(); 
			$user->user_type = Role::find($user->role_id)->role ?? '';

			return response()->json([
				'token' => $user->createToken('myApp')->accessToken,
				'user' => $user], 200); 
			} 
			else{ 
			return response()->json(['error'=>'email or password is incorrect'], 422); 
			} 
	}

}
