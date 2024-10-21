<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseaseCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disease_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid("cooperative_id")->nullable();
            $table->foreign('cooperative_id')->references('id')->on('cooperatives');
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
        Schema::dropIfExists('disease_categories');
    }
}
