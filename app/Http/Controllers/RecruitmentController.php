<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recruitment;
use App\RecruitmentApplication;
use App\EmploymentType;
use App\JobPosition;
use App\Events\AuditTrailEvent;
use Illuminate\Support\Facades\Auth;
use DB;
use Log;
use Hash;
use Carbon\Carbon;

class RecruitmentController extends Controller
{
    //return recruitments view
    public function index()
    {
        $coop = Auth::user()->cooperative->id;
        $recruitments = Recruitment::where('cooperative_id', $coop)->latest()->get();
        $positions = JobPosition::where('cooperative_id',$coop)->latest()->get();
        $types = EmploymentType::where('cooperative_id',$coop)->latest()->get();
        return view('pages.cooperative.hr.recruitment.index', compact('recruitments','positions','types'));
    }
    //edit
    public function edit($id)
    {
        $coop = Auth::user()->cooperative->id;
        $recruitment = Recruitment::find($id);
        $positions = JobPosition::where('cooperative_id',$coop)->latest()->get();
        $types = EmploymentType::where('cooperative_id',$coop)->latest()->get();
        return view('pages.cooperative.hr.recruitment.edit', compact('recruitment','positions','types','id'));
    }
    //store
    public function store(Request $req)
    {
        $req->validate([
            'role',
            'description',
            'desired_skills',
            'qualifications',
            'employment_type',
            'end_date',
        ]);
        try {
            DB::beginTransaction();
            $coop = Auth::user()->cooperative->id;
            $coop_name = Auth::user()->cooperative->name;
            $post = new Recruitment();
            $post->role = $req->role;
            $post->description = $req->description;
            $post->desired_skills = $req->desired_skills;
            $post->qualifications = $req->qualifications;
            $post->employment_type = $req->employment_type;
            $post->salary_range = $req->salary_range;
            $post->location = $req->location;
            //upload file
            $file_link='';
            if($req->file('file'))
            {
                $extensions = array("png","jpg","jpeg","pdf","doc","docx");
                $result = array($req->file('file')->guessExtension());
                
                if(!in_array($result[0],$extensions)){
                    return response()->json("File must be pdf, image or word document", 422);
                }
                //upload file
                $files = $req->file('file');
                $destinationPath = 'files/cooperative/recruitment-files/'; // upload path
                $file = "recruitment_".date('YmdHis') . "." . $files->guessExtension();
                $files->move($destinationPath, $file);
                $file_link =  '/'.$destinationPath.$file;                    
            }
            $post->file = $file_link;
            $post->status = 0;
            $post->end_date = Carbon::create($req->end_date);
            $post->cooperative_id = $coop;
            $post->save();
            //log
            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Created recruitment post '.$req->role.'for '.$coop_name, 'cooperative_id'=> Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));

            DB::commit();
            toastr()->success('Recruitment Post Created Successfully');
            return redirect()->route('hr.recruitments.show');
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollback();
            toastr()->error('Recruitment Post failed to create');
            return redirect()->route('hr.recruitments.show');
        }
    }
    // /update
    public function update(Request $req)
    {
        $req->validate([
            'role',
            'description',
            'desired_skills',
            'qualifications',
            'employment_type',
            'end_date',
        ]);
        try {
            DB::beginTransaction();
            $coop = Auth::user()->cooperative->id;
            $coop_name = Auth::user()->cooperative->name;
            $post = Recruitment::find($req->id);
            $post->role = $req->role;
            $post->description = $req->description;
            $post->desired_skills = $req->desired_skills;
            $post->qualifications = $req->qualifications;
            $post->employment_type = $req->employment_type;
            $post->salary_range = $req->salary_range;
            $post->location = $req->location;
            //upload file
            $file_link='';
            if($req->file('file'))
            {
                $extensions = array("png","jpg","jpeg","pdf","doc","docx");
                $result = array($req->file('file')->guessExtension());
                
                if(!in_array($result[0],$extensions)){
                    return response()->json("File must be pdf, image or word document", 422);
                }
                //upload file
                $files = $req->file('file');
                $destinationPath = 'files/cooperative/recruitment-files/'; // upload path
                $file = "recruitment_".date('YmdHis') . "." . $files->guessExtension();
                $files->move($destinationPath, $file);
                $file_link =  '/'.$destinationPath.$file;                    
            }
            $post->file = $file_link;
            $post->status = 0;
            $post->end_date = Carbon::create($req->end_date);
            $post->cooperative_id = $coop;
            $post->save();
            //log
            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Updated recruitment post #'.$req->id.' '.$req->role.'for '.$coop_name, 'cooperative_id'=> Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));

            DB::commit();
            toastr()->success('Recruitment Post Updated Successfully');
            return redirect()->route('hr.recruitments.show');
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollback();
            toastr()->error('Recruitment Post failed to update');
            return redirect()->route('hr.recruitments.show');
        }
    }
    //close
    public function close($id)
    {
        try {
            $coop_name = Auth::user()->cooperative->name;
            DB::beginTransaction();

            $post = Recruitment::find($id);
            $post->status = 1;
            $post->save();
            //log
            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Closed recruitment post '.$post['role'].'for '.$coop_name, 'cooperative_id'=> Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));

            DB::commit();
            toastr()->success('Recruitment Post Closed Successfully');
            return redirect()->route('hr.recruitments.show');
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollback();
            toastr()->error('Recruitment Post failed to close');
            return redirect()->route('hr.recruitments.show');
        }
    }
    //
    public function delete($id)
    {
        try {
            $coop_name = Auth::user()->cooperative->name;
            DB::beginTransaction();
            $post = Recruitment::find($id);
            $post->delete();
            //log
            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Deleted recruitment post '.$post['role'].'for '.$coop_name, 'cooperative_id'=> Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));

            DB::commit();
            toastr()->success('Recruitment Post Deleted Successfully');
            return redirect()->route('hr.recruitments.show');
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollback();
            toastr()->error('Recruitment Post failed to delete');
            return redirect()->route('hr.recruitments.show');
        }
    }
    //recruitmens for api
    public function getPosts()
    {
        $recruitments = Recruitment::whereDate('end_date', '>=', now())->with(['cooperative'])->latest()->get();
        return $recruitments;
    }

    //recruitmen applications
    public function applications($id)
    {
        $recruitment = Recruitment::find($id);
        $applications = RecruitmentApplication::where('recruitment_id',$id)->with(['recruitment'])->latest()->get();
        return view('pages.cooperative.hr.recruitment.applications', compact('applications','recruitment'));
    }

}
