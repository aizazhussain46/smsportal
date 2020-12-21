<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Revision;
use Validator;
use App\Job;

class RevisionController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }
    
    public function index()
    {
        return Revision::all();
    }

    
     public function store(Request $request){
        

        $arr = [
            's_id' => $request->s_id,
            'r_id' => $request->r_id,
            'job_id' => $request->job_id,
            'msg' => $request->msg,
        ];

        $created = Revision::create($arr);
        $flag = false;
        if($created){
            if($request->s_id == 2 || $request->r_id == 2){
                $flag = true;
            }
            else{
                $flag = true;
                $updated = Job::where('id', $request->job_id)->update(['status_id' => 3]); 
            }
            
            if($flag){
                return response()->json(['success' => true, 'data' => Revision::find($created->id)]);
            }
            else{
                return response()->json(['success' => false, 'data' => '']);
            }

        }
        else{
            return response()->json(['success' => false, 'data' => '']);
        }


    }

    public function revision(Request $request,$id)
    {

        // SELECT * FROM `revisions` WHERE 
        // `job_id` = 6 AND `s_id` = 4 AND `r_id` = 2 
        //  OR 
        // `job_id` = 6 AND `s_id` = 2 AND `r_id` = 4

        $s = $request->s_id;
        $r = $request->r_id;
	
        $revisions = Revision::where('job_id',$id)
        ->where('s_id',$s)->where('r_id',$r)
        ->orWhere('job_id',$id)->where('s_id',$r)->where('r_id',$s)        
        ->get();

        return response()->json([ 'success' => true, 'data' => $revisions ], 200);
    }

 
}
