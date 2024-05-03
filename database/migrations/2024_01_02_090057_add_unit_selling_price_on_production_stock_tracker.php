<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitSellingPriceOnProductionStockTracker extends Migration
{
    public function up()
    {
        Schema::table('production_stock_tracker', function (Blueprint $table) {
            $table->double('selling_price')->after('final_product_id');
        });
    }

    public function down()
    {
        Schema::table('production_stock_tracker', function (Blueprint $table) {
            $table->dropColumn('selling_price');
        });
    }
}
