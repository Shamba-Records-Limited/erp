<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerExpectedYieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmer_expected_yields', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->double('quantity')->default(0);
            $table->uuid('crop_id')->nullable();
            $table->uuid('livestock_id')->nullable();
            $table->enum('volume_indicator', config('enums.volume_indicator'));
            $table->uuid('farm_unit_id');
            $table->uuid('cooperative_id');
            $table->foreign('farm_unit_id')->references('id')->on('farm_units')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('crop_id')->references('id')->on('crops')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('livestock_id')->references('id')->on('cows')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::dropIfExists('farmer_expected_yields');
    }
}
