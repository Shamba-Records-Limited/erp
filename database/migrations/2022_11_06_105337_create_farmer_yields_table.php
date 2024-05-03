<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerYieldsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('farmer_yields', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('farmer_id');
            $table->enum('type', ['farm', 'livestock','farm_tracker'])->default('farm');
            $table->uuid('crop_id')->nullable();
            $table->uuid('livestock_id')->nullable();
            $table->string('product')->nullable();
            $table->date('date')->nullable();
            $table->uuid('expected_yields_id')->nullable();
            $table->double('volume_indicator_count')->default(0);
            $table->float('yields')->default(0);
            $table->uuid('unit_id');
            $table->text('comments')->nullable();
            $table->uuid('cooperative_id');
            $table->foreign('unit_id')->references('id')->on('units')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('crop_id')->references('id')->on('crops')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('livestock_id')->references('id')->on('cows')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('farmer_id')->on('farmers')->references('id')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreign('expected_yields_id')->on('farmer_expected_yields')->references('id')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmer_yields');
    }
}
