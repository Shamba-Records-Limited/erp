<?php

namespace App\Http\Traits;

use App\FarmerCropProgressTracker;

trait Farm
{
    public function farmer_crop_calendar($farmer_crop_id): \Illuminate\Http\JsonResponse
    {
        $trackers = FarmerCropProgressTracker::where('farmer_crop_id', $farmer_crop_id)->orderBy('created_at')->get();
        $data = [];
        $prev = null;
        foreach ($trackers as $t) {
            $color_index = get_random_number($prev, 0, count(COLORS) - 1);
            if ($t->farmer_crop->crop_id) {
                $title = $t->stage->name . ': ' . $t->farmer_crop->crop->name . '(' . $t->farmer_crop->crop->variety . ')';
            } else {
                $title = $t->stage->name . ': ' . $t->farmer_crop->livestock->name . '(' . $t->farmer_crop->livestock->breed->name . ')';
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

        return response()->json($data);
    }
}
