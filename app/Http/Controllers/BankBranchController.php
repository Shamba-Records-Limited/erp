<?php

namespace App\Http\Controllers;

use App\Bank;
use App\BankBranch;
use App\Events\AuditTrailEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankBranchController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $banks = Bank::select('id','name')->where('cooperative_id',Auth::user()->cooperative->id)->latest()->get();
        $bank_branches = BankBranch::where('cooperative_id', Auth::user()->cooperative->id)->latest()->get();
        return view('pages.cooperative.bank.branch', compact('banks','bank_branches'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            "name" => "required|string",
            "code" => "required|string",
            'address' => 'required|string',
            'bank_id' => 'required|string',
        ]);

        $bank_branch = new BankBranch();
        $this->persist($bank_branch,$request);
        $data = ['user_id' => Auth::user()->id, 'activity' => 'created  '.$request->name.' Bank Branch','cooperative_id'=> Auth::user()->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Bank Branch Created Successfully');
        return redirect()->route('cooperative.bank_branch.show');
    }

    private function persist($bank_branch, $request)
    {
        $bank_branch->name = $request->name;
        $bank_branch->code = $request->code;
        $bank_branch->address = $request->address;
        $bank_branch->bank_id = $request->bank_id;
        $bank_branch->cooperative_id = Auth::user()->cooperative->id;
        $bank_branch->save();
    }
}
