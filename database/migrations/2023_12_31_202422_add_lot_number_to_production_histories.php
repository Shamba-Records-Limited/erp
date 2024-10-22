<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLotNumberToProductionHistories extends Migration
{
    public function up()
    {
        Schema::table('production_histories', function (Blueprint $table) {
            $table->string('production_lot')->after('production_id');
        });
    }


    public function down()
    {
        Schema::table('production_histories', function (Blueprint $table) {
            $table->dropColumn(['production_lot']);
        });
    }
}
