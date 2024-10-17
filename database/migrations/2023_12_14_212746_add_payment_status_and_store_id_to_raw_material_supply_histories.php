<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentStatusAndStoreIdToRawMaterialSupplyHistories extends Migration
{
    public function up()
    {
        Schema::table('raw_material_supply_histories', function (Blueprint $table) {
            $table->smallInteger('payment_status')->default(1)->after('quantity');
            $table->uuid('store_id')->nullable()->after('payment_status');
            $table->foreign('store_id')->references('id')
                ->on('manufacturing_stores')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
        });
    }

    public function down()
    {
        Schema::table('raw_material_supply_histories', function (Blueprint $table) {
            $table->dropForeign('raw_material_supply_histories_store_id_foreign');
            $table->dropColumn(['store_id','payment_status']);
        });
    }
}
