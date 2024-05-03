<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCropCalendarStageCostBreakdownsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crop_calendar_stage_cost_breakdowns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('item');
            $table->integer('amount');
            $table->uuid('tracker_id')->nullable();
            $table->foreign('tracker_id')->references('id')->on('farmer_crop_progress_trackers')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crop_calendar_stage_cost_breakdowns');
    }
}
