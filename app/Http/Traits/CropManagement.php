<?php

namespace App\Http\Traits;

use App\CropCalendarStage;
use App\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

trait CropManagement
{
    /**
     * @throws Throwable
     */
    public function addCalendarStages($data, User $user){
        try {
            DB::beginTransaction();
            foreach (json_decode($data->stages) as $stage){
                $calendarStage = new CropCalendarStage();
                $calendarStage->name = $stage->name;
                $calendarStage->type = $data->type;
                $calendarStage->period = $stage->period;
                $calendarStage->period_measure = 'days';
                $calendarStage->crop_id = $data->crop;
                $calendarStage->livestock_id = $data->livestock;
                $calendarStage->cooperative_id = $user->cooperative_id;
                $calendarStage->save();
            }
            DB::commit();
        }catch (Exception | Throwable  $e){
            DB::rollBack();
            throw new Exception("Failed to save stages: ".$e->getMessage());
        }
    }
}
