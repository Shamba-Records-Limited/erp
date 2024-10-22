<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiryDateAndProductCountAndBatchNumberToProductions extends Migration
{
    public function up()
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->integer('production_count')->default(0)
                ->after('final_selling_price');
            $table->date('expiry_date')
                ->default(\Carbon\Carbon::now()->addDays(10))
                ->after('production_count');
        });
    }

    public function down()
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropColumn(['production_count','expiry_date']);
        });
    }
}
