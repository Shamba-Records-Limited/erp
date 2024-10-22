<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveSessionOnCooperativeFinancialPeriods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cooperative_financial_periods', function (Blueprint $table) {
            $table->boolean('active')->default(false)->after('balance_bf');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cooperative_financial_periods', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
