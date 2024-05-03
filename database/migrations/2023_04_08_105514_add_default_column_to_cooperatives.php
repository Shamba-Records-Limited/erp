<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultColumnToCooperatives extends Migration
{
    public function up()
    {
        Schema::table('cooperatives', function (Blueprint $table) {
            $table->smallInteger('default_coop')->default(0)->after('id');
        });
    }

    public function down()
    {
        Schema::table('cooperatives', function (Blueprint $table) {
            $table->dropColumn('default_coop');
        });
    }
}
