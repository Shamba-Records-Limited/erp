<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidAmountToFarmerAuctionOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmer_auction_orders', function (Blueprint $table) {
            $table->double('paid_amount', 15, 2)->default(0.00)->after('farmer_id')->index();
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
            $table->dropColumn('paid_amount');
        });
    }
}
