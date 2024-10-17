<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvanceDeductionsTable extends Migration
{
    public function up()
    {
        Schema::create('advance_deductions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->smallInteger('type');
            $table->smallInteger('start_month');
            $table->smallInteger('start_year');
            $table->double('monthly_deductions');
            $table->double('principal_amount');
            $table->double('balance');
            $table->smallInteger('status');
            $table->uuid('employee_id');
            $table->foreign('employee_id')
                ->references('id')
                ->on('coop_employees')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->uuid('created_by');
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('advance_deductions');
    }
}
