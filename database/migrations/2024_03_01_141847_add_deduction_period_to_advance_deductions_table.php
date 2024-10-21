<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeductionPeriodToAdvanceDeductionsTable extends Migration
{
    public function up()
    {
        Schema::table('advance_deductions', function (Blueprint $table) {
            $table->integer('deduction_period')->default(0)->after('status');
        });
    }

    public function down()
    {
        Schema::table('advance_deductions', function (Blueprint $table) {
            $table->dropColumn('deduction_period');
        });
    }
}
