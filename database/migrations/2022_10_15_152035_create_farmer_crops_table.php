<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerCropsTable extends Migration
{
    /**
     * Run the migrations.
     *'farmer_id','crop_id','stage_id','last_date','next_stage_id','cost','cooperative_id'
     * @return void
     */
    public function up()
    {
        Schema::create('farmer_crops', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('farmer_id');
            $table->uuid('crop_id');
            $table->uuid('stage_id');
            $table->date('last_date');
            $table->uuid('next_stage_id')->nullable();
            $table->double('total_cost');
            $table->enum('status', config('enums.farmer_crop_status'));
            $table->uuid('cooperative_id');
            $table->timestamps();
            $table->foreign('farmer_id')->references('id')->on('farmers')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('crop_id')->references('id')->on('crops')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('stage_id')->references('id')->on('crop_calendar_stages')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('next_stage_id')->references('id')->on('crop_calendar_stages')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmer_crops');
    }
}
