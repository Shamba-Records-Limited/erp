<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmployeeStatusToPayrollTable extends Migration
{
    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->smallInteger('employee_status')
                ->after('paye')
                ->default(\App\CoopEmployee::STATUS_ACTIVE);
        });
    }


    public function down()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('employee_status');
        });
    }
}
