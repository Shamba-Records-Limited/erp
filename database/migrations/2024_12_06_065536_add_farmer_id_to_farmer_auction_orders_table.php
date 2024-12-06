<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFarmerIdToFarmerAuctionOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmer_auction_orders', function (Blueprint $table) {
            $table->char('farmer_id', 36)->nullable()->after('user_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farmer_auction_orders', function (Blueprint $table) {
            $table->dropColumn('farmer_id');
        });
    }
}
