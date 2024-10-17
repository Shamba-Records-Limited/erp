<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerCropProgressTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmer_crop_progress_trackers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('farmer_crop_id');
            $table->uuid('stage_id');
            $table->date('last_date');
            $table->uuid('next_stage_id')->nullable();
            $table->double('cost');
            $table->enum('status', config('enums.farmer_crop_status'));
            $table->timestamps();
            $table->foreign('farmer_crop_id')->references('id')->on('farmer_crops')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('stage_id')->references('id')->on('crop_calendar_stages')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('next_stage_id')->references('id')->on('crop_calendar_stages')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmer_crop_progress_trackers');
    }
}
