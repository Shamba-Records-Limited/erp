<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_deductions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->smallInteger('deduction_stage')->default(\App\PayrollDeduction::BEFORE_PAYE_DEDUCTION);
            $table->double('min_amount')->nullable();
            $table->double('max_amount')->nullable();
            $table->double('amount')->nullable();
            $table->double('rate')->nullable();
            $table->uuid('country_id');
            $table->foreign('country_id')
                ->on('countries')
                ->references('id')
                ->onDelete('RESTRICT')
                ->onUpdate('CASCADE');
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
        Schema::dropIfExists('payroll_deductions');
    }
}
