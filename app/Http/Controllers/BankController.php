<?php

namespace App\Http\Controllers;

use App\Bank;
use App\Events\AuditTrailEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BankController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }


    public function index()
    {
        $banks = Bank::where('cooperative_id',Auth::user()->cooperative->id)->latest()->get();
        return view('pages.cooperative.bank.index', compact('banks'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            "name" => "required|string",
            "swift_code" => "required|string",
            'contact_no' => 'required|regex:/^[0-9]{10}$/',
        ]);
        $bank = new Bank();
        try {
            DB::beginTransaction();
            $this->persist($bank,$request);
            DB::commit();
            $data = ['user_id' => Auth::user()->id, 'activity' => 'created  '.$request->name.' Bank','cooperative_id'=> Auth::user()->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Bank Created Successfully');
            return redirect()->route('cooperative.bank.show');

        }catch (\Throwable $e)
        {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }

    }

    private function persist($bank, $request)
    {
        try {
            DB::beginTransaction();
            $bank->name = $request->name;
            $bank->contact_no = $request->contact_no;
            $bank->swift_code = $request->swift_code;
            $bank->cooperative_id = Auth::user()->cooperative->id;
            $bank->save();
            DB::commit();

        }catch (\Throwable $exception)
        {
            DB::rollBack();
            Log::error($exception->getMessage());
        }

    }


    public function edit(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request,[
            "name" => "required|string",
            "swift_code" => "required|string",
            'contact_no' => 'required|regex:/^[0-9]{10}$/',
        ]);

        try {

            $bank = Bank::findOrFail($id);
            DB::beginTransaction();
            $this->persist($bank,$request);
            DB::commit();
            $data = ['user_id' => Auth::user()->id, 'activity' => 'Edited  '.$bank->name.' Bank','cooperative_id'=> Auth::user()->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Bank Edit Successfully');
            return redirect()->route('cooperative.bank.show');

        }catch(\Throwable $e)
        {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }

    }


}
