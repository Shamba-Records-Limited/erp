<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\Http\Controllers\Controller;
use App\Lot;
use App\LotGradeDistribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class LotsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $lots = DB::select(DB::raw("
            SELECT l.* FROM lots l
        "));

        return view('pages.cooperative-admin.lots.index', compact("lots"));
    }

    public function detail(Request $request, $lot_number)
    {
        $lots = DB::select(DB::raw("
            SELECT l.*,
                (SELECT SUM(c.quantity) FROM collections c WHERE c.lot_number = l.lot_number) as total_collection_quantity,
                (SELECT SUM(d.quantity) FROM lot_grade_distributions d WHERE d.lot_number = l.lot_number) as total_graded_quantity
            FROM lots l
            WHERE l.lot_number = :lot_number
        "), ["lot_number" => $lot_number]);

        $lot = null;
        if (count($lots) > 0) {
            $lot = $lots[0];
        }

        $lot_unit = Lot::find($lot_number)->unit;

        $action = $request->query('action', '');
        $tab = $request->query('tab', 'collections');

        $collections = [];
        if ($tab == 'collections') {
            $collections = DB::select(DB::raw("
                SELECT c.*, pc.unit FROM collections c
                JOIN products p ON p.id = c.product_id
                JOIN product_categories pc ON pc.id = p.category_id
                WHERE c.lot_number = :lot_number;
            "), ["lot_number" => $lot_number]);
        }

        $gradeDistributions = [];
        if ($tab == 'grade_distributions') {
            $gradeDistributions = DB::select(DB::raw("
                SELECT d.*, g.name as grade FROM lot_grade_distributions d
                JOIN product_grades g ON g.id = d.product_grade_id
                WHERE lot_number = :lot_number;
            "), ["lot_number" => $lot_number]);
        }

        $grades = [];
        if ($action == 'add_grade_distribution'){
            $grades = DB::select(DB::raw("
                SELECT g.* FROM product_grades g;
            "));
        }

        return view('pages.cooperative-admin.lots.detail', compact("lot", "lot_unit", "action", "tab", "collections", "gradeDistributions", "grades"));
    }

    public function store_grade_distribution(Request $request, $lot_number)
    {
        $request->validate([
            'product_grade_id' => 'required',
            'quantity' => 'required',
        ]);

        $lot = Lot::find($lot_number);

        DB::beginTransaction();
        try {
            // check ungraded quantity
            $lot_metrics = DB::select(DB::raw("
                SELECT
                    (SELECT SUM(c.quantity) FROM collections c WHERE c.lot_number = l.lot_number) as total_collection_quantity,
                    (SELECT SUM(d.quantity) FROM lot_grade_distributions d WHERE d.lot_number = l.lot_number) as total_graded_quantity
                FROM lots l
                WHERE l.lot_number = :lot_number
            "), ["lot_number" => $lot_number]);

            $ungraded_quantity = 0;
            if (count($lot_metrics) > 0) {
                $ungraded_quantity = $lot_metrics[0]->total_collection_quantity - $lot_metrics[0]->total_graded_quantity;
            }

            if ($request->quantity > $ungraded_quantity) {
                DB::rollBack();
                toastr()->error("Quantity exceeds ungraded quantity($ungraded_quantity) for this lot");
                return redirect()->back();
            }

            $gradeDistribution = new LotGradeDistribution();
            $gradeDistribution->lot_number = $lot_number;
            $gradeDistribution->product_grade_id = $request->product_grade_id;
            $gradeDistribution->quantity = $request->quantity;
            $gradeDistribution->unit = $lot->unit;
            $gradeDistribution->save();

            DB::commit();

            toastr()->success('Grade distribution saved');
            return redirect()->route('cooperative-admin.lots.detail', [$lot_number, "tab" => "grade_distributions"]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to save grade distribution');
            return redirect()->back();
        }
    }
}
