<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountyGovtOfficialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('county_govt_officials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('country_code');
            $table->uuid('county');
            $table->string('gender');
            $table->string('id_no')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('employee_no')->nullable();
            $table->uuid('user_id');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('county_govt_officials');
    }
}
