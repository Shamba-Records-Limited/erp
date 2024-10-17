<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayeDeductionOnPayrollsTable extends Migration
{

    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->double('paye_before_deduction')->after('taxable_income')->default(0);
            $table->text('paye_deduction')->after('paye_before_deduction');
        });
    }


    public function down()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['paye_deduction','paye_before_deduction']);
        });
    }
}
