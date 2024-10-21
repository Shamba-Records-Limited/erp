<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmployeeAllowanceTypeFromEnumToStringOnEmployeeAllowancesTable extends Migration
{
    public function up()
    {
        Schema::table('employee_allowances', function (Blueprint $table) {
            $table->string('type', 100)->change();
        });
    }

    public function down()
    {
        Schema::table('employee_allowances', function (Blueprint $table) {
            echo "No changes";
        });
    }
}
