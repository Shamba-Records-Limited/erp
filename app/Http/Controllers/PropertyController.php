<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Http\Request;
use App\CooperativeProperty;
use App\Events\AuditTrailEvent;
use Illuminate\Support\Facades\Auth;
use DB;
use Log;

class PropertyController extends Controller
{

    const FILE_DESTINATION = 'files/cooperative/asset-files/';

    public function index()
    {
        $coop = Auth::user()->cooperative->id;
        $property = CooperativeProperty::where('cooperative_id', $coop)->get();
        return view('pages.cooperative.property.index', compact('property'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'property' => 'required',
            'deprecation_rate_pa' => 'required',
            'property_type' => 'required',
            'buying_price' => 'required',
        ]);

        try {
            $user = Auth::user();
            DB::beginTransaction();
            //new asset
            $coop = $user->cooperative->id;
            $coop_name = $user->cooperative->name;

            $property = new CooperativeProperty();
            $property->property = $request->property;
            $property->deprecation_rate_pa = $request->deprecation_rate_pa;
            $property->type = $request->property_type;
            //upload
            $file_link = '';
            if ($request->has('file')) {
                $extensions = array("png", "jpg", "jpeg", "pdf", "doc", "docx");
                $result = array($request->file('file')->guessExtension());

                if (!in_array($result[0], $extensions)) {
                    toastr()->error('File must be pdf, image or word document');
                    return redirect()->back()->withInput()->withErrors(["file" => "File must be pdf, image or word document"]);
                }
                //upload file
                $files = $request->file('file');
                $destinationPath = self::FILE_DESTINATION; // upload path
                $file = "asset_" . date('YmdHis') . "." . $files->guessExtension();
                $files->move($destinationPath, $file);
                $file_link = '/' . $destinationPath . $file;

            }
            $property->documents = $file_link;

            $property->value = $request->buying_price;
            $property->selling_price = $request->selling_price;
            $property->buying_price = $request->buying_price;
            $property->status = $request->status;
            $property->cooperative_id = $coop;
            $property->save();

            //audit trail log
            $activity_log = ['user_id' => $user->id, 'activity' => 'Added Coopertive property ' . $request->name .
                ' to  ' . $coop_name, 'cooperative_id' => $coop];
            event(new AuditTrailEvent($activity_log));
            $data = [
                "date" => date('Y-m-d'),
                "income" => null,
                "expense" => $property->buying_price,
                "particulars" => "Pay Farmer",
                "user_id" => $user->id,
                "cooperative_id" => $user->cooperative_id,
            ];
            $record_expenditure = has_recorded_income_expense($data);
            
            $trx = create_account_transaction('Property Purchase', $property->buying_price, "Purchase of property: {$property->property}");
            if ($record_expenditure && $trx) {
                DB::commit();
                toastr()->success('Property added Successfully');
                return redirect()->back();
            } else {
                DB::rollBack();
                toastr()->error('Ooops! Request could not be processed');
                return redirect()->back();
            }


        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Property failed to be added');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'property' => 'required',
            'deprecation_rate_pa' => 'required',
            'property_type' => 'required',
            'buying_price' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $user = Auth::user();
            //new asset
            $coop = $user->cooperative->id;
            $coop_name = $user->cooperative->name;

            $property = CooperativeProperty::find($request->id);
            $property->property = $request->property;
            $property->deprecation_rate_pa = $request->deprecation_rate_pa;
            $property->type = $request->property_type;
            //upload
            if ($request->has('file')) {
                if (File::exists(public_path($property->documents))) {
                    File::delete(public_path($property->documents));
                }

                $extensions = array("png", "jpg", "jpeg", "pdf", "doc", "docx");
                $result = array($request->file('file')->guessExtension());

                if (!in_array($result[0], $extensions)) {
                    toastr()->error('File must be pdf, image or word document');
                    return redirect()->back();
                }
                //upload file
                $files = $request->file('file');
                // upload path
                $file = "asset_" . date('YmdHis') . "." . $files->guessExtension();
                $files->move(self::FILE_DESTINATION, $file);
                $file_link = '/' . self::FILE_DESTINATION . $file;
                $property->documents = $file_link;
            }

            $property->value = $request->buying_price;
            $property->selling_price = $request->selling_price;
            $property->buying_price = $request->buying_price;
            $property->status = $request->status;
            $property->cooperative_id = $coop;
            $property->save();

            //audit trail log
            $activity_log = ['user_id' => $user->id, 'activity' => 'Updated Coopertive property ' . $request->id .
                ' to  ' . $coop_name, 'cooperative_id' => $coop];
            event(new AuditTrailEvent($activity_log));
            
            $trx = create_account_transaction('Property Purchase', $property->buying_price, "Purchase of property: {$property->property}");
            if ($trx) {
                DB::commit();
                toastr()->success('Property updated Successfully');
                return redirect()->back();
            }
            DB::rollBack();
            toastr()->error("Oops! Error occurred");
            return redirect()->back();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Property failed to be updated');
            return redirect()->back();
        }
    }

    public function deleteProperty($id): \Illuminate\Http\RedirectResponse
    {
        try {
            $user = Auth::user();
            $property = CooperativeProperty::findOrFail($id);
            if (File::exists(public_path($property->documents))) {
                File::delete(public_path($property->documents));
            }
            $property->delete();
            $activity_log = ['user_id' => $user->id, 'activity' => 'Deleted property asset: ' . $property->id, 'cooperative_id' => $user->cooperative_id];
            event(new AuditTrailEvent($activity_log));
            toastr()->success('Property Deleted');
            return redirect()->back();

        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            toastr()->error('Operation failed');
            return redirect()->back();
        }

    }
}
