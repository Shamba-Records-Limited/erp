<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('payroll_extras');
        Schema::dropIfExists('employee_payrolls');
        Schema::create('payrolls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_id');
            $table->double('gross_pay');
            $table->double('net_pay');
            $table->double('basic_pay');
            $table->double('total_allowances')->default(0);
            $table->text('allowances')->nullable();
            $table->text('before_tax_deductions')->nullable();
            $table->text('after_tax_deductions')->nullable();
            $table->uuid('created_by');
            $table->uuid('cooperative_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('employee_id')->on('coop_employees')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('cooperative_id')->on('cooperatives')
                ->references('id')
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
        Schema::dropIfExists('payrolls');
    }
}
