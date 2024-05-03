<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAllowancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_allowances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->double('amount', 13,4);
            $table->enum('type', ['deduction','benefit']);//deduction or benefit
            $table->string('title');
            $table->text('description')->nullable();

            $table->uuid('employee_id');
            $table->foreign('employee_id')->references('id')->on('coop_employees')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::dropIfExists('employee_allowances');
    }
}
