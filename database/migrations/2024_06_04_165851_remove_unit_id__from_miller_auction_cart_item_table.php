<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnitIdFromMillerAuctionCartItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('miller_auction_cart_item', function (Blueprint $table) {
            //
            $table->dropForeign('miller_auction_cart_item_unit_id_foreign');
            $table->dropColumn('unit_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('miller_auction_cart_item', function (Blueprint $table) {
            //
            $table->uuid('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
