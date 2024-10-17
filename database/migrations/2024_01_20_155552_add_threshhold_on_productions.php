<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThreshholdOnProductions extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->double('threshold')->default(5)->after('unit_id');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('threshold');
        });
    }
}
