<?php

namespace App\Http\Controllers;

use App\Events\AuditTrailEvent;
use App\Exports\VetItemsExport;
use App\Unit;
use App\VetItem;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VetItemsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $units = Unit::where('cooperative_id', Auth::user()->cooperative->id)->latest()->get();
        $vet_items = VetItem::where('cooperative_id', Auth::user()->cooperative->id)->latest()->get();
        return view('pages.cooperative.vets.items.index', compact('units', 'vet_items'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'item_name' => 'required|string',
            'unit_measure' => 'required',
            'quantity' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'buying_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'selling_price' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        try {
            DB::beginTransaction();
            $amount = ($request->quantity * $request->buying_price);
            $trx = create_account_transaction('Purchase Vet Items', $amount, 'Stock vet items');
            if ($trx) {
                $item = new VetItem();
                $this->save($request, $item);

                $data = ['user_id' => Auth::user()->id, 'activity' => 'created  ' . $request->item_name . ' vet item', 'cooperative_id' => Auth::user()->cooperative->id];
                event(new AuditTrailEvent($data));
                toastr()->success('Item Created Successfully');
                DB::commit();
                return redirect()->route('cooperative.vet.items.show');
            } else {
                DB::rollback();
                toastr()->error('Oops! Operation failed');
            }

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back();
        }
    }

    private function save($request, $item)
    {
        $item->name = $request->item_name;
        $item->unit_id = $request->unit_measure;
        $item->quantity = $request->quantity;
        $item->bp = $request->buying_price;
        $item->sp = $request->selling_price;
        $item->cooperative_id = Auth::user()->cooperative->id;
        $item->save();
    }
    public function export_vet_items($type)
    {
        $cooperative_id = Auth::user()->cooperative_id;
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('vet_items_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new VetItemsExport($cooperative_id), $file_name);
        } else {
            $data = [
                'title' => 'Vet Items',
                'pdf_view' => 'vet_items',
                'records' => VetItem::where('cooperative_id', $cooperative_id)->latest()->get(),
                'filename' => strtolower('vet_items_' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }
}
