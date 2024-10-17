<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportedCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reported_cases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('farmer_id');
            $table->uuid('disease_id');
            $table->text('symptoms');
            $table->enum('status', config('enums.disease_status'))->default('Mild');
            $table->boolean('booked')->default(false);
            $table->uuid('cooperative_id');
            $table->foreign('farmer_id')->on('users')->references('id')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('disease_id')->on('diseases')->references('id')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('cooperative_id')->on('cooperatives')->references('id')->onUpdate('cascade')->onDelete('restrict');
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
        Schema::dropIfExists('reported_cases');
    }
}
