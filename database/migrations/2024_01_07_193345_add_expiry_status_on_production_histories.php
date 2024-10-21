<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiryStatusOnProductionHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_histories', function (Blueprint $table) {
            $table->smallInteger('expiry_status')->after('expiry_date')->default(\App\ProductionHistory::EXPIRY_STATUS_NOT_EXPIRED);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_histories', function (Blueprint $table) {
            $table->dropColumn('expiry_status');
        });
    }
}
