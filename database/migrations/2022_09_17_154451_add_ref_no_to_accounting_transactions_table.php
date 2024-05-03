<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefNoToAccountingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $default_ref_no = strtoupper(date('M').date('dyhis').date_default_timezone_get().date('D'));
        Schema::table('accounting_transactions', function (Blueprint $table) use ($default_ref_no) {
            $table->string('ref_no')->default($default_ref_no)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting_transactions', function (Blueprint $table) {
            $table->dropColumn(['ref_no']);
        });
    }
}
