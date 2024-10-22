<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpgradeAuctionOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('miller_auction_order_item', function (Blueprint $table) {
            $table->dropForeign('miller_auction_order_item_product_category_id_foreign');
            $table->dropColumn('product_category_id');

            $table->float("quantity");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //

        Schema::table('miller_auction_order_item', function (Blueprint $table) {
            $table->dropColumn('quantity');

            $table->uuid("product_category_id");
            $table->foreign('product_category_id')->references('id')->on('product_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
