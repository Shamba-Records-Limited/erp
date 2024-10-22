<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CoopEmployee;
use App\EmployeeLeave;
use App\Events\AuditTrailEvent;
use App\Exports\HrManagementLeaveExport;
use Illuminate\Support\Facades\Auth;
use DB;
use Log;
use EloquentBuilder;
use Excel;

class EmployeeLeaveController extends Controller
{
    //view leaves
    public function index()
    {
        $coop = Auth::user()->cooperative->id;
        $leaves = EloquentBuilder::to(EmployeeLeave::whereHas('employee', function ($query) use ($coop) {
            $query->whereHas('user', function ($query2) use ($coop) {
                $query2->where('cooperative_id', $coop);
            });
        }), request()->all())->latest()->get();
        return view('pages.cooperative.hr.leave.index', compact('leaves'));
    }
    //create
    public function store(Request $req)
    {
        $req->validate([
            'employee_no' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'reason' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $leave = new EmployeeLeave();
            $leave->start_date = $req->start_date;
            $leave->end_date = $req->end_date;
            $leave->reason = $req->reason;
            $leave->remarks = $req->remarks;
            //upload file
            $file_link = '';
            if ($req->file('file')) {
                $extensions = array("png", "jpg", "jpeg", "pdf", "doc", "docx");
                $result = array($req->file('file')->guessExtension());

                if (!in_array($result[0], $extensions)) {
                    return response()->json("File must be pdf, image or word document", 422);
                }
                //upload file
                $files = $req->file('file');
                $destinationPath = 'files/cooperative/leave-files/'; // upload path
                $file = "leave_" . date('YmdHis') . "." . $files->guessExtension();
                $files->move($destinationPath, $file);
                $file_link =  '/' . $destinationPath . $file;
            }
            $leave->file = $file_link;
            $leave->status = $req->status;
            //get employee 
            $employee = CoopEmployee::where('employee_no', $req->employee_no)->first();
            $leave->employee_id = $employee->id;
            $leave->save();

            //log
            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Created leave for ' . $employee->first_name . ' of Number: .' . $employee->first_name, 'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));

            DB::commit();
            toastr()->success('Leave Created Successfully');
            return redirect()->route('hr.leaves.show');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            toastr()->error('Leave could not be created');
            return redirect()->route('hr.leaves.show');
        }
    }
    public function update(Request $req)
    {
        $req->validate([
            'employee_no' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'reason' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $leave = EmployeeLeave::find($req->id);
            $leave->start_date = $req->start_date;
            $leave->end_date = $req->end_date;
            $leave->reason = $req->reason;
            $leave->remarks = $req->remarks;
            //upload file
            $file_link = '';
            if ($req->file('file')) {
                $extensions = array("png", "jpg", "jpeg", "pdf", "doc", "docx");
                $result = array($req->file('file')->guessExtension());

                if (!in_array($result[0], $extensions)) {
                    return response()->json("File must be pdf, image or word document", 422);
                }
                //upload file
                $files = $req->file('file');
                $destinationPath = 'files/cooperative/leave-files/'; // upload path
                $file = "leave_" . date('YmdHis') . "." . $files->guessExtension();
                $files->move($destinationPath, $file);
                $file_link =  '/' . $destinationPath . $file;
            }
            $leave->file = $file_link;
            $leave->status = $req->status;
            //get employee 
            $employee = CoopEmployee::where('id', $leave['employee_id'])->first();
            $leave->save();

            //log
            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Updated leave #' . $req->id . ' for ' . $employee->first_name . ' of Number: .' . $employee->employee_no, 'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));

            DB::commit();
            toastr()->success('Leave updated Successfully');
            return redirect()->route('hr.leaves.show');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            toastr()->error('Leave could not be updated');
            return redirect()->route('hr.leaves.show');
        }
    }
    //change
    public function change($id)
    {
        try {
            DB::beginTransaction();
            $leave = EmployeeLeave::find($id);
            $leave->status = request()->get('status');
            //get employee 
            $employee = CoopEmployee::where('id', $leave['employee_id'])->first();
            $leave->save();

            //log
            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Updated leave #' . $id . ' for ' . $employee->first_name . ' of Number: .' . $employee->employee_no, 'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));

            DB::commit();
            toastr()->success('Leave updated Successfully');
            return redirect()->route('hr.leaves.show');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            toastr()->error('Leave could not be updated');
            return redirect()->route('hr.leaves.show');
        }
    }
    //delete
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $leave = EmployeeLeave::find($id);
            $leave->delete();
            //log
            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Deleted leave #' . $id, 'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));

            DB::commit();
            toastr()->success('Leave deleted Successfully');
            return redirect()->route('hr.leaves.show');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            toastr()->error('Leave could not be deleted');
            return redirect()->route('hr.leaves.show');
        }
    }

    public function export_registered_employees_leaves($type)
    {
        $cooperative = Auth::user()->cooperative->id;

        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('registered_employees_leaves_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new HrManagementLeaveExport($cooperative), $file_name);
        } else {
            $data = [
                'title' => 'Registered Employees Leaves',
                'pdf_view' => 'registered_employees_leaves',
                'records' => EloquentBuilder::to(EmployeeLeave::whereHas('employee', function ($query) use ($cooperative) {
                    $query->whereHas('user', function ($query2) use ($cooperative) {
                        $query2->where('cooperative_id', $cooperative);
                    });
                }), request()->all())->latest()->get(),
                'filename' => strtolower('registered_employees_leaves_' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }
}
