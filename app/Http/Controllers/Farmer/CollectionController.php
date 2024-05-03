<?php

namespace App\Http\Controllers\Farmer;

use App\Charts\CollectionsChart;
use App\Collection;
use App\CollectionQualityStandard;
use App\Events\AuditTrailEvent;
use App\Http\Controllers\Controller;
use App\Product;
use App\User;
use Carbon\Carbon;
use EloquentBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //farmer view
    public function farmerIndex()
    {
        $farmer = Auth::user()->farmer->id;
        $collections = EloquentBuilder::to(Collection::where('farmer_id', $farmer)
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED), request()->all())->latest()->get();
        $products = Product::with(['unit'])->latest()->get();
        return view('pages.cooperative.collections.farmer.index', compact('collections', 'products'));
    }

    ///get farmer report data
    public function getFarmerReports()
    {
        $farmer = Auth::user()->farmer->id;
        $latest_collections = Collection::where('farmer_id', $farmer)->with(['farmer', 'product', 'agent'])
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)->whereDate('created_at', '>=', now()->subMonth(1))->latest()->get();
        $collection_count = Collection::where('farmer_id', $farmer)->with(['farmer', 'product', 'agent'])
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)->whereDate('created_at', '>=', now()->subMonth(1))->latest()->count();

        $label1 = now()->subDays(7)->format('D d');
        $label2 = now()->subDays(6)->format('D d');
        $label3 = now()->subDays(5)->format('D d');
        $label4 = now()->subDays(4)->format('D d');
        $label5 = now()->subDays(3)->format('D d');
        $label6 = now()->subDays(2)->format('D d');
        $label7 = now()->subDays(1)->format('D d');

        $day1 = Collection::where('farmer_id', $farmer)
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)->whereDate('created_at', '=', now()->subDays(6))->count();
        $day2 = Collection::where('farmer_id', $farmer)
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)->whereDate('created_at', '=', now()->subDays(5))->count();
        $day3 = Collection::where('farmer_id', $farmer)
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)->whereDate('created_at', '=', now()->subDays(4))->count();
        $day4 = Collection::where('farmer_id', $farmer)
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)->whereDate('created_at', '=', now()->subDays(3))->count();
        $day5 = Collection::where('farmer_id', $farmer)
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)->whereDate('created_at', '=', now()->subDays(2))->count();
        $day6 = Collection::where('farmer_id', $farmer)
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)->whereDate('created_at', '=', now()->subDays(1))->count();
        $day7 = Collection::where('farmer_id', $farmer)
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)->whereDate('created_at', '=', now())->count();
        //products
        $collection_products = Collection::where('farmer_id', $farmer)
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)->with(['product'])->whereDate('created_at', '>=', now()->subMonth(1))->latest()->take(10)->get();
        $collection_farmers = Collection::where('farmer_id', $farmer)
            ->where('submission_status', Collection::SUBMISSION_STATUS_APPROVED)->with(['farmer'])->whereDate('created_at', '>=', now()->subMonth(1))->latest()->take(10)->get();

        $data = [
            'latest' => $latest_collections,
            'count_collections' => $collection_count,
            'products' => $collection_products,
            'farmers' => $collection_farmers,
        ];
        $borderColors = ["#72a014", "#72a014", "#72a014", "#72a014", "#72a014", "#72a014", "#72a014", "#72a014"];
        $fillColors = ["#2bb930", "#2bb930", "#2bb930", "#2bb930", "#2bb930", "#2bb930", "#2bb930", "#2bb930"];
        $collections_chart = new CollectionsChart();
        $collections_chart->minimalist(false);
        $collections_chart->labels([$label1, $label2, $label3, $label4, $label5, $label6, $label7]);
        $collections_chart->dataset('Done Last 1 Week', 'bar', [$day1, $day2, $day3, $day4, $day5, $day6, $day7])
            ->color($borderColors)
            ->backgroundcolor($fillColors);

        return view('pages.cooperative.collections.farmer.report', compact('data', 'collections_chart'));
    }

    public function collections()
    {
        $user = Auth::user();
        $collections = Collection::where('farmer_id', $user->farmer->id)->get();
        $products = Product::select('products.id', 'products.name', 'units.name as unit')
            ->join('farmers_products', 'farmers_products.product_id', '=', 'products.id')
            ->join('units', 'units.id', '=', 'products.unit_id')
            ->where('farmer_id', $user->id)->get();
        $quality_standards = CollectionQualityStandard::getStandardQualities($user->cooperative_id);
        return view('pages.as-farmer.collections.index', compact('collections', 'products', 'quality_standards'));
    }

    public function addCollection(Request $request)
    {
        $request->validate([
            'product' => 'required',
            'quantity' => 'required|numeric',
            'standard_id' => 'required'
        ],
            [
                'standard_id.required' => 'Quality standard is required'
            ]);
        $user = Auth::user();
        $batch = Carbon::now()->format('DyMds');
        $collection = new Collection();
        $request = [
            "farmerId" => $user->farmer->id,
            "productId" => $request->product,
            "availableQuantity" => $request->quantity,
            "batchNo" => strtoupper($batch),
            "agentId" => $user->id,
            "cooperative" => $user->cooperative_id,
            "quality" => $request->standard_id,
            "comments" => $request->comments,
            "submission_status" => Collection::SUBMISSION_STATUS_PENDING
        ];
        $collection = $collection->saveCollection($request);
        $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Submitted collection of product batch # ' . $collection->batch_no . ' from farmer #' . $user->id,
            'cooperative_id' => $user->cooperative_id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Collection Submitted successfully');
        return redirect()->back();
    }

}
