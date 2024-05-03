<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoopEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coop_employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('country_id');
            $table->uuid('county_of_residence');
            $table->string('area_of_residence')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('dob');
            $table->string('gender');
            $table->string('id_no')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('employee_no')->nullable();
            $table->string('kra')->nullable();
            $table->string('nhif_no')->nullable();
            $table->string('nssf_no')->nullable();
            $table->uuid('department_id');
            $table->uuid('user_id');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('department_id')->references('id')->on('coop_branch_departments')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::dropIfExists('coop_employees');
    }
}
