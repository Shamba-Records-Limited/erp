<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\Collection;
use App\CoopBranch;
use App\Exports\CollectionExport;
use App\Http\Controllers\Controller;
use App\Product;
use App\Unit;
use Carbon\Carbon;
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Log;

class CollectionsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $farmers = DB::select(DB::raw("
                SELECT
                    f.id,
                    u.username
                FROM farmers f
                JOIN countries c ON f.country_id = c.id
                JOIN users u ON f.user_id = u.id
                JOIN farmer_cooperative fc ON fc.farmer_id = f.id;
            "));

        $grading = DB::select(DB::raw("
            SELECT g.id, g.name FROM product_grades g;
        "));

        $products = DB::select(DB::raw("
            SELECT p.* FROM products p;
        "));

        $coop = Auth::user()->cooperative;
        // if user is assigned to branch use the default product for branch
        $default_product_id = null;
        $default_product_ids = DB::select(DB::raw("
            SELECT c.main_product_id FROM cooperatives c
            WHERE c.id = :id
        "), ["id" => $coop->id]);
        if (count($default_product_ids) > 0) {
            $default_product_id = $default_product_ids[0]->main_product_id;
        }

        $coopBranches = DB::select(DB::raw("
            SELECT b.id, b.name FROM coop_branches b
            WHERE b.cooperative_id = :id
        "), ["id" => $coop->id]);


        $units = Unit::all();

        $collections = DB::select(DB::raw("
            SELECT usr.username, p.name as product_name, quantity, u.abbreviation as unit_abbr, c.*
            FROM collections c
            JOIN farmers f ON f.id = c.farmer_id
            JOIN users usr ON usr.id = f.user_id
            JOIN products p ON p.id = c.product_id
            JOIN units u ON u.id = c.unit_id
        "));

        return view('pages.cooperative-admin.collections.index', compact(
            'collections','products', 'farmers', 'grading', 'default_product_id', 'coopBranches', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "coop_branch_id" => "required",
            "farmer_id" => "required",
            "product_id" => "required",
            "product_grade_id" => "required",
            "quantity" => "required",
            "unit_id" => "required",
            "collection_time" => "required",
        ]);

        $coop = Auth::user()->cooperative;

        try {
            $collection = new Collection();
            $collection->cooperative_id = $coop->id;
            $collection->coop_branch_id = $request->coop_branch_id;
            $collection->farmer_id = $request->farmer_id;
            $collection->product_id = $request->product_id;
            $collection->product_grade_id = $request->product_grade_id;
            $collection->quantity = $request->quantity;
            $collection->unit_id = $request->unit_id;
            $collection->collection_time = $request->collection_time;
            $collection->comments = $request->comments;
            $collection->lot_number = strtoupper(Carbon::now()->format('Ymd'));
            $collection->date_collected = Carbon::now();
            $collection->save();

            toastr()->success('Collection Added Successfully');
            return redirect()->route('cooperative-admin.collections.show');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }

    public function export_collection($type)
    {
        $cooperative = Auth::user()->cooperative->id;
        // if ($request->request_data == '[]') {
        //     $request = null;
        // } else {
        //     $request = json_decode($request->request_data);
        // }

        $collections = Collection::where("cooperative_id", $cooperative)->get();

        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('collections_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new CollectionExport($collections), $file_name);
        } else {
            $data = [
                'title' => 'Collections',
                'pdf_view' => 'collections',
                'records' => $collections,
                'filename' => strtolower('collections_' . date('d_m_Y')),
                'orientation' => 'portrait'
            ];
            return download_pdf($data);
        }
    }
}
