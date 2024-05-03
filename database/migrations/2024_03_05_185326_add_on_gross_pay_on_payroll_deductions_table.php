<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnGrossPayOnPayrollDeductionsTable extends Migration
{
    public function up()
    {
        Schema::table('payroll_deductions', function (Blueprint $table) {
            $table->smallInteger('on_gross_pay')->default(0)->after('rate');
        });
    }


    public function down()
    {
        Schema::table('payroll_deductions', function (Blueprint $table) {
            $table->dropColumn('on_gross_pay');
        });
    }
}
