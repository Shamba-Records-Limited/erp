<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerCooperativeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmer_cooperative', function (Blueprint $table) {
            $table->id();
            $table->uuid("farmer_id");
            $table->foreign('farmer_id')->references('id')->on('farmers')->onUpdate('cascade')->onDelete('cascade');
            $table->uuid("cooperative_id");
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('cascade')->onDelete('cascade');
            $table->uuid("added_by_id")->nullable();
            $table->foreign('added_by_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('farmer_cooperative');
    }
}
