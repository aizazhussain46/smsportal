<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\Emailsend;
use App\User;
use App\Role;
use App\Status;
use App\District;
use App\Department;
use App\Job;
use Illuminate\Support\Facades\Auth;
use Validator;
class JobController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
	}
  
    public function index()
    {

        $jobs = Job::orderBy('id', 'DESC')->get();
		return response()->json([ 'success' => true, 'data' => $jobs ] ,200);
    }

    public function jobs_for_pmu()
    {

        $jobs = Job::orderBy('id', 'DESC')->whereIn('status_id',[1,2,3,4,6,7,8,9,10])->get();

		return response()->json([ 'success' => true, 'data' => $jobs ] ,200);
    }

    public function jobs_for_client()
    {

        $jobs = Job::orderBy('id', 'DESC')->where('status_id',10)->get();

		return response()->json([ 'success' => true, 'data' => $jobs ] ,200);
    }

    public function jobs_by_created_and_assigned($id)
    {

        $jobs = Job::orderBy('id', 'DESC')->where('created_by',$id)->orWhere('assigned_to',$id)->get();

		return response()->json([ 'success' => true, 'data' => $jobs ] ,200);
    }

    public function jobs_by_assigned_user($id)
    {

        $jobs = Job::orderBy('id', 'DESC')->where('assigned_to',$id)->get();

		return response()->json([ 'success' => true, 'data' => $jobs ] ,200);
    }

    public function btl_records($user_id)
    {
        $jobs = Job::orderBy('id', 'DESC')->where('assigned_to', $user_id)->where('job_type',2)->whereIn('status_id',[1,2,3,6,7,9])->get();

		return response()->json([ 'success' => true, 'data' => $jobs ] ,200);
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [ 
            'job_type' => 'required',
            'task_title' => 'required',
			'nature_of_task' => 'required', 
			'deliverables' => 'required', 
			'from' => 'required',
			'to' => 'required'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		
		]); 

        }

        $attachment = array();

        if($request->hasFile('attachment')){
            $att = $request->attachment;
            foreach($att as $attach){
                $img = $attach->getClientOriginalName();
                $attach->move(public_path('uploads/attachments/'),$img);
                $attachment[] = asset('uploads/attachments/' . $img);
            }
        }
        else{
            $attachment[] = asset('uploads/attachments/no-img.png');
        }
        
        $arr = [
            'job_type' => $request->job_type,
            'task_title' => $request->task_title,
            'nature_of_task' => $request->nature_of_task,
            'brief' => $request->brief,
            'deliverables' => $request->deliverables,
            'created_by' => $request->created_by,
            'department_id' => $request->department_id,
            '_from' => $request->from,
            '_to' => $request->to,
            'from_time' => $request->from_time,
            'to_time' => $request->to_time,
            'district_id' => $request->district_id,
            'status_id' => 8,
            'attachment' => $attachment

        ];
        
        $created = Job::create($arr);
        if($created){
            $job = Job::find($created->id);
            $to = $job->created_by_user->email;
            $subject = "New Job";
            $message = "Job has been created successfully. Job created by ".$job->created_by_user->name;
            $mail = Mail::to($to)->send(new Emailsend($message, $subject));
            // $message = "Job has been created successfully";
            // $mail = Mail::to("aizaz.hussain@orangeroomdigital.com")->send(new Emailsend($to, $subject));
           
        }
        
        list($status,$data) = $created ? [ true , $created] : [ false , ''] ;

        return response()->json(['success' => $status, 'data' => $data]);
        
    }

    public function show($id)
    {
		return response()->json([ 'success' => true, 'data' => Job::where('id', $id)->first() ]);
    }

    public function get_job_count($id = null)
    {        
		return $id ? Job::where('job_type', $id)->count() : Job::count();
    }

  

    public function update_job(Request $request, $id)
    {
        $attachment = array();

        if($request->hasFile('attachment')){

            $att = $request->attachment;
            foreach($att as $attach){
                $img = $attach->getClientOriginalName();
                $attach->move(public_path('uploads/attachments/'),$img);
                $attachment[] = asset('uploads/attachments/' . $img);
            }
        }
        else{ $attachment = Job::find($id)->attachment; }
        
        $request->job_type != 1 ? $request->department_id = null : $request->district_id = null ;

        $arr = [
            'job_type' => $request->job_type,
            'task_title' => $request->task_title,
            'nature_of_task' => $request->nature_of_task,
            'deliverables' => $request->deliverables,
            'brief' => $request->brief,
            'department_id' => $request->department_id,
            '_from' => $request->from,
            '_to' => $request->to,
            'district_id' => $request->district_id,
            'attachment' => $attachment
        ];
        
        $job = Job::find($id);
       
        if($job->assigned_to == null && $request->assigned_to != 'null'){
            $arr['status_id'] = 1;
            $arr['assigned_to'] = $request->assigned_to;
            $updated = Job::where('id', $id)->update($arr);
            $this->job_update_fun($id,'c');

        }
        else if($job->assigned_to != null && $request->assigned_to != 'null' && $request->assigned_to != $job->assigned_to){

            $arr['status_id'] = 3;
            $arr['assigned_to'] = $request->assigned_to;
            $updated = Job::where('id', $id)->update($arr);
            $this->job_update_fun($id,'c');

        }
        else{
            
            $updated = Job::where('id', $id)->update($arr);
            $this->job_update_fun($id,'u');
            
        }


       
        list($status,$data) = $updated 
        ? [ true , Job::find($id)] 
        : [ false , ''] ;

        return response()->json(['success' => $status, 'data' => $data]);

    }
    public function job_update_fun($id,$flag){
            
            $job = Job::find($id);
            if($flag == 'c'){
                $to = $job->assigned_to_user->email;
                $bcc = $job->created_by_user->email;
                $subject = "Job Assigned";
                $message = "Job has been assigned to ".$job->assigned_to_user->name;
            }
            else{
                $to = $job->created_by_user->email;
                $bcc = null;
                $subject = "Job Updated";
                $message = "Job has been updated successfully";
            }
           
            $this->send_email_for_job($to,$bcc,$subject,$message);
    }
    public function send_email_for_job($to,$bcc = null,$subject,$msg)
    {
        if($bcc){
            return Mail::to($to)->bcc($bcc)->send(new Emailsend($msg, $subject));
        }
        else{
            return Mail::to($to)->send(new Emailsend($msg, $subject));
        }
    }

    public function change_status(Request $request, $id)
    {

        $data = '';
        $updated = Job::where('id', $id)->update(['status_id' =>  $request->sw ? 2 : 4]); 
        if($updated){
            $data = Job::find($id);
            $bcc = $data->assigned_to_user->email ?? '';
            $to = $data->created_by_user->email;
            $subject = "Status Updated";
            $message = "Status has been updated successfully.";
            $mail = Mail::to($to)->bcc($bcc)->send(new Emailsend($message, $subject));
        }
        else{
            $data = array();
        }
        return response()->json(['success' => $updated ? true : false, 'data'=>$data]);
    }
    public function approve_reject($id, $action)
    {
        $data = '';
        if($action == 'a'){
            $subject = "Job Approved";
            $message = "Job has been Approved successfully.";
            $updated = Job::where('id', $id)->update(['status_id' => 6]); 
        }
        else{
            $subject = "Job Rejected";
            $message = "Job has been Rejected.";
            $updated = Job::where('id', $id)->update(['status_id' => 5]); 
        }
        if($updated){
            $data = Job::find($id);
            $to = $data->created_by_user->email;
            $mail = Mail::to($to)->send(new Emailsend($message, $subject));
        }
        else{
            $data = array();
        }
        
        return response()->json(['success' => $updated ? true : false, 'data'=>$data]);
    }

    public function update_attachment(Request $request, $id)
    {
        $attachment = array();

        if($request->hasFile('attachment')){

            $att = $request->attachment;
            
            foreach($att as $attach){
                $img = $attach->getClientOriginalName();
                $attach->move(public_path('uploads/attachments/'),$img);
                $attachment[] = asset('uploads/attachments/' . $img);
            }
        }
        else{ $attachment = Job::find($id)->attachment; }
        
        $updated = Job::where('id', $id)->update(['attachment' => $attachment]); 
        if($updated){
            $data = Job::find($id);
            $bcc = $data->assigned_to_user->email ?? null;
            $to = $data->created_by_user->email;
            $subject = "Attachment Updated";
            $message = "Attachment has been updated successfully.";
            if($bcc){
                $mail = Mail::to($to)->bcc($bcc)->send(new Emailsend($message, $subject));
            }
            else{
                $mail = Mail::to($to)->send(new Emailsend($message, $subject));
            }
            
        }
        return response()->json(['success' =>  $updated ? true : false, 'attachment' => $attachment]);
    }

    public function add_attachment(Request $request, $id)
    {
        $attachment = Job::find($id)->attachment;

        if($request->hasFile('attachment')){

            $att = $request->attachment;
            
            foreach($att as $attach){
                $img = $attach->getClientOriginalName();
                $attach->move(public_path('uploads/attachments/'),$img);
                $attachment[] = asset('uploads/attachments/' . $img);
            }
        }
        
        $updated = Job::where('id', $id)->update(['attachment' => $attachment]); 
        // if($updated){
        //     $data = Job::find($id);
        //     $bcc = $data->assigned_to_user->email ?? null;
        //     $to = $data->created_by_user->email;
        //     $subject = "Attachment Updated";
        //     $message = "Attachment has been updated successfully.";
        //     if($bcc){
        //         $mail = Mail::to($to)->bcc($bcc)->send(new Emailsend($message, $subject));
        //     }
        //     else{
        //         $mail = Mail::to($to)->send(new Emailsend($message, $subject));
        //     } 
        // }
        return response()->json(['success' =>  $updated ? true : false, 'attachment' => $attachment]);
    }

    public function share(Request $request, $id)
    {
        $role_id = User::find($request->user_id)->role_id;

        $updated = Job::where('id', $id)->update(['status_id' => !$role_id ? 9 : 10]); 
        if($updated){
            $job = Job::find($id);
            $bcc = $job->assigned_to_user->email ?? '';
            $to = $job->created_by_user->email;
            $subject = "Job Shared";
            $message = "Job has been Shared Successfully.";
            $mail = Mail::to($to)->bcc($bcc)->send(new Emailsend($message, $subject));
        }
        list($status,$data) = $updated ? [ true , Job::find($id) ] : [ false , ''] ;

        return response()->json(['success' => $status, 'data' => $data]);

    }

    public function destroy($id)
    {
        $job = Job::find($id);
        $to = $job->created_by_user->email;
        $delete = Job::find($id)->delete();
        if($delete){
            $subject = "Job Deleted";
            $message = "Job has been Deleted Successfully.";
            $mail = Mail::to($to)->send(new Emailsend($message, $subject));
        }
        return ($delete) 
                ? [ 'response_status' => true, 'message' => 'Job has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'Job cannot delete' ] ;
    }

    public function complete_job($id)
    {

        $data = '';
        $updated = Job::where('id', $id)->update(['status_id' => 7]); 
        if($updated){
            $data = Job::find($id);
            $to = $data->created_by_user->email;
            $subject = "Job Completed";
            $message = "Job has been Completed Successfully.";
            $mail = Mail::to($to)->send(new Emailsend($message, $subject));
        }
        else{
            $data = array();
        }
        return response()->json(['success' => $updated ? true : false, 'data'=>$data]);
    }


    public function send_email(){
        // $job = Job::find(1);
        // echo "<pre>";
        // return $job->created_by_user->email;
        $data = "Job has been created successfully";
        //$mail = Mail::to($customer_email)->bcc($cs->email)->bcc($driver->email)->send(new Emailsend($data));
        $mail = Mail::to("aizazkalwar46@gmail.com")->send(new Emailsend($data, 'Test email SSEP'));
        //return view('invoice', $data);

    }
}
