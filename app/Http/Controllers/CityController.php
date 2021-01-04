<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use Illuminate\Support\Facades\Auth;
use Validator;
class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
	}
    
    public function index()
    {
        $data = City::all();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
			'city_name' => 'required'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		]); 
		}

        $input = $request->all(); 
        $city = City::create($input); 
		return response()->json([
			'success' => true,
			'data' => $city
		],200); 
    }

    public function show($id)
    {
        return response()->json(City::find($id));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
			'city_name' => 'required'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		]); 
		}

		$input = $request->all(); 
        $role = City::where('id', $id)->update($input); 
        $get = City::find($id);
		return response()->json([
			'success' => true,
			'data' => $get
		],200); 
    }

    public function destroy($id)
    {
        return (City::find($id)->delete()) 
                ? [ 'response_status' => true, 'message' => 'City has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'City cannot delete' ];
    }
}
