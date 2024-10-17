<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBalanceToRawMaterialSupplyHistories extends Migration
{
    public function up()
    {
        Schema::table('raw_material_supply_histories', function (Blueprint $table) {
            $table->double('balance')->default(0)->after('amount');
        });
    }

    public function down()
    {
        Schema::table('raw_material_supply_histories', function (Blueprint $table) {
            $table->dropColumn('balance');
        });
    }
}
