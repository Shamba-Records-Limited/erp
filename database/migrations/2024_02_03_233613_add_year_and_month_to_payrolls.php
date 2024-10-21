<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYearAndMonthToPayrolls extends Migration
{
    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->smallInteger('period_month')->after('employee_id')->index();
            $table->smallInteger('period_year')->after('period_month')->index();
            $table->double('taxable_income')->after('after_tax_deductions')->index();
            $table->double('paye')->after('taxable_income')->index();
        });
    }


    public function down()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropIndex('payrolls_period_month_index',
                'payrolls_period_year_index',
                'payrolls_paye_index',
                'payrolls_taxable_income_index');
            $table->dropColumn(['period_year','period_month','paye','taxable_income']);
        });
    }
}
