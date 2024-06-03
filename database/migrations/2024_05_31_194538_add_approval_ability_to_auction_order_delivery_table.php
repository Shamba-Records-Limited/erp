<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalAbilityToAuctionOrderDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auction_order_delivery', function (Blueprint $table) {
            //
            $table->timestamp("approved_at")->nullable();
            $table->uuid("approved_by")->nullable(); // approver
            $table->foreign('approved_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auction_order_delivery', function (Blueprint $table) {
            //
            $table->dropColumn("approved_at");
            $table->dropForeign("auction_order_delivery_approved_by_foreign");
            $table->dropColumn("approved_by");
        });
    }
}
