<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvanceDeductionTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('advance_deduction_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('payroll_id');
            $table->foreign('payroll_id')
                ->references('id')
                ->on('payrolls')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->double('amount');
            $table->double('balance');
            $table->uuid('advance_deduction_id');
            $table->foreign('advance_deduction_id')
                ->references('id')
                ->on('advance_deductions')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->uuid('cooperative_id');
            $table->foreign('cooperative_id')
                ->references('id')
                ->on('cooperatives')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('advance_deduction_transactions');
    }
}
