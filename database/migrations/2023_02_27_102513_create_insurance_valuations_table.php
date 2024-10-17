<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceValuationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_valuations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('farmer_id');
            $table->string('type');
            $table->double('amount');
            $table->string('description');
            $table->uuid('cooperative_id');
            $table->timestamps();
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('farmer_id')
                ->references('id')
                ->on('farmers')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_valuations');
    }
}
