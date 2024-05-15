<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMillerBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miller_branches', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('name');
            $table->string('code')->nullable();
            $table->uuid('miller_id');
            $table->foreign('miller_id')->references('id')->on('millers')->onUpdate('cascade')->onDelete('cascade');
            $table->string('county_id');
            $table->foreign('county_id')->references('id')->on('counties')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger("sub_county_id");
            $table->foreign('sub_county_id')->references('id')->on('sub_counties')->onUpdate('cascade')->onDelete('cascade');
            $table->string('location');
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
        Schema::dropIfExists('miller_branches');
    }
}
