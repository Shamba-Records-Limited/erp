<?php

namespace App\Http\Controllers;

use App\Breed;
use App\Cow;
use App\Crop;
use App\FarmerCrop;
use App\FarmerCropProgressTracker;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CowBreedMiniDashboardController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $coop = Auth::user()->cooperative;
        $cooperative = $coop->id;
        $from = Carbon::now()->subYear()->format('Y-m-d');
        $to = Carbon::now()->addDay()->format('Y-m-d');
        $breeds = Breed::where('cooperative_id', $cooperative)->count();
        $cows = Cow::where('cooperative_id', $cooperative)->count();
        $total_production_cost = FarmerCrop::where('cooperative_id', $cooperative)
            ->whereBetween('created_at', [$from, $to])->sum('total_cost');
        $crops = Crop::where('cooperative_id', $cooperative)->count();

        return view('pages.cooperative.minidashboards.herd-management', compact('breeds', 'cows', 'total_production_cost', 'coop', 'crops'));
    }

    public function stats()
    {
        $cooperative = Auth::user()->cooperative->id;
        $herds_grouping = $this->get_herds_grouped_by_breed($cooperative);
        $calendar_data = $this->crop_calendar_data($cooperative);
        $crops_by_breed = $this->crops_grouped_by_variety($cooperative);
        $data = ['herds_grouping' => $herds_grouping, 'calendar_data' => $calendar_data, 'crops_by_breed'=>$crops_by_breed];
        return json_encode($data);
    }

    private function get_herds_grouped_by_breed($cooperative)
    {
        return DB::select("
        SELECT COUNT(c.id) AS count, b.name AS breed FROM cows c JOIN breeds b ON c.breed_id = b.id WHERE c.cooperative_id = '$cooperative'
        GROUP BY b.name  ORDER BY count DESC LIMIT 7;
        ");
    }

    private function crops_grouped_by_variety($cooperative): array
    {
        return DB::select("SELECT COUNT(product_id) AS count, variety FROM crops WHERE cooperative_id = '$cooperative' 
                                              GROUP BY product_id,variety  ORDER BY count DESC LIMIT 7;");
    }


    private function crop_calendar_data($coop)
    {

        $cooperative_farm_crops_id = FarmerCrop::select('id')->where('cooperative_id', $coop)->pluck('id')->toArray();
        $trackers = FarmerCropProgressTracker::whereIn('farmer_crop_id', $cooperative_farm_crops_id)->orderBy('created_at')->get();
        $data = [];
        $prev = null;
        foreach ($trackers as $t) {
            $color_index = get_random_number($prev, 0, count(COLORS) - 1);
            $farmer = $t->farmer_crop->farmer->user->first_name . ' ' . $t->farmer_crop->farmer->user->other_names;
            if($t->farmer_crop->type == 1){
                $title = $farmer . ': ' . $t->stage->name . ': ' .
                    ($t->farmer_crop->crop->product ? $t->farmer_crop->crop->product->name . '(' . $t->farmer_crop->crop->variety . ')' : '-');
            }else{
                $title = $farmer . ': ' . $t->stage->name . ': '. $t->farmer_crop->livestock->name .'(' . $t->farmer_crop->livestock->breed->name . ')';
            }

            $prev = $color_index;
            $res = [
                "start" => $t->start_date,
                "end" => $t->last_date,
                "title" => ucwords(strtolower($title)),
                "color" => COLORS[$color_index]
            ];

            $data[] = $res;
        }
        return $data;
    }
}
