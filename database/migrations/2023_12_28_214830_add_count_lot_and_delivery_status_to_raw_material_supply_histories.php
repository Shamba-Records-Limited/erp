<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountLotAndDeliveryStatusToRawMaterialSupplyHistories extends Migration
{
    public function up()
    {
        Schema::table('raw_material_supply_histories', function (Blueprint $table) {
            $table->string('purchase_number')->after('details');
            $table->smallInteger('delivery_status')->after('purchase_number');
        });
    }

    public function down()
    {
        Schema::table('raw_material_supply_histories', function (Blueprint $table) {
            $table->dropColumn(['purchase_number','delivery_status']);
        });
    }
}
