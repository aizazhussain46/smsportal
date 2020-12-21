<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Status;
use App\District;
use App\Department;
use App\Job;
use App\Revision;
use App\Survey;
use Illuminate\Support\Facades\Auth;
use Validator;
class ReportController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:api');
    }
    public function index(Request $request){
        $type = $request->job_type;
        $status = $request->status;
        $department = $request->department;
        $created_by = $request->created_by;
        $assigned_to = $request->assigned_to;
        $district = $request->district;
        $timestamp = $request->timestamp;
        $from = $request->from;
        $to = $request->to;
        $args = array();
        $user_id = $request->user_id;
        $user = User::find($user_id);
        if($user->master == 1 || $user->role_id == 1){
        }
        else if($user->role_id == 2){
            $created_by = $user_id;
        }
        else{
            $assigned_to = $user_id;
        }

        $now = date('Y-m-d', strtotime('+1 day'));
        $bw = array('', $now);
        if($timestamp){
            if($timestamp == 'today'){
                $today = date('Y-m-d');
                $args[] = array('created_at', '>', $today);
            }
            else if($timestamp == 'week'){
                $week = date('Y-m-d', strtotime('-1 week'));
                $args[] = array('created_at', '>=', $week);
            }
            else if($timestamp == 'month'){
                $month = date('Y-m-d', strtotime('-1 month'));
                $args[] = array('created_at', '>=', $month); 
            }
            else if ($timestamp == 'year'){
                $year = date('Y-m-d', strtotime('-1 year'));
                $args[] = array('created_at', '>=', $year); 
            }
        }
        else if($from && $to){
            $from = date('Y-m-d', strtotime($from));
            $to = date('Y-m-d', strtotime($to.' +1 day'));
            $bw = array($from, $to);
        }
        if($type){
            $args[] = array('job_type', '=', $type);
        }
        if($status){
            $args[] = array('status_id', '=', $status);
        }
        if($department){
            $args[] = array('department_id', '=', $department);
        }
        if($created_by){
            $args[] = array('created_by', '=', $created_by);
        }
        if($assigned_to){
            $args[] = array('assigned_to', '=', $assigned_to);
        }
        if($district){
            $args[] = array('district_id', '=', $district);
        }

        //return array($bw, $args);
        $jobs = Job::where($args)
        ->whereBetween('created_at', $bw)
        ->orderBy('id', 'DESC')->get();


        foreach($jobs as $job){ 
            if($job->department){
                $job->department_name = $job->department->department;
            }
            if($job->district){
                $job->district_name = $job->district->district;
            }
            if($job->assigned_to_user){
                $job->assigned_name = $job->assigned_to_user->name;
            }
            $job->created_name = $job->created_by_user->name;        
            $job->status_name = $job->status->status;
            $job->keyword_name = $job->status->keyword;
            //dump($job->status);
        }
        
        //dd($job);
		return response()->json([ 'success' => true, 'data' => $jobs ] ,200);
    }

    public function survey(Request $request){
        $created_by = $request->created_by;
        $district = $request->district;
        $timestamp = $request->timestamp;
        $from = $request->from;
        $to = $request->to;
        $job = $request->job;
        $args = array();
        $user_id = $request->created_by;
        if($user_id){
                $user = User::find($user_id);
                if($user->master == 1){
                }
                else{
                    $created_by = $user_id;
                }
        }
        $now = date('Y-m-d', strtotime('+1 day'));
        $bw = array('', $now);
        if($timestamp){
            if($timestamp == 'today'){
                $today = date('Y-m-d');
                $args[] = array('created_at', '>', $today);
            }
            else if($timestamp == 'week'){
                $week = date('Y-m-d', strtotime('-1 week'));
                $args[] = array('created_at', '>=', $week);
            }
            else if($timestamp == 'month'){
                $month = date('Y-m-d', strtotime('-1 month'));
                $args[] = array('created_at', '>=', $month); 
            }
            else if ($timestamp == 'year'){
                $year = date('Y-m-d', strtotime('-1 year'));
                $args[] = array('created_at', '>=', $year); 
            }
        }
        else if($from && $to){
            $from = date('Y-m-d', strtotime($from));
            $to = date('Y-m-d', strtotime($to.' +1 day'));
            $bw = array($from, $to);
        }
        
        if($created_by){
            $args[] = array('user_id', '=', $created_by);
        }
        if($district){
            $args[] = array('district_id', '=', $district);
        }
        if($job){
            $args[] = array('job_id', '=', $job);
        }

        $surveys = Survey::where($args)
        ->whereBetween('created_at', $bw)
        ->orderBy('id', 'DESC')->get();
        return response()->json([ 'success' => true, 'data' => $surveys ] ,200);
    }
  
}
