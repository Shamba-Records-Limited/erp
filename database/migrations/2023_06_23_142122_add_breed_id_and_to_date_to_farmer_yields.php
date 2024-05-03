<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBreedIdAndToDateToFarmerYields extends Migration
{

    public function up()
    {
        Schema::table('farmer_yields', function (Blueprint $table) {
            $table->dropForeign('farmer_yields_livestock_id_foreign');
            $table->dropColumn('livestock_id');
            $table->date('to_date')->after('date')->nullable();
            $table->uuid('livestock_breed_id')->after('crop_id')->nullable();
            $table->smallInteger('frequency_type')->after('unit_id')->default(1);
            $table->foreign('livestock_breed_id')->references('id')->on('breeds')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    public function down()
    {
        Schema::table('farmer_yields', function (Blueprint $table) {
            $table->dropForeign('farmer_yields_livestock_breed_id_foreign');
            $table->dropColumn('livestock_breed_id');
            $table->dropColumn('to_date');
            $table->uuid('livestock_id')->after('crop_id')->nullable();
            $table->foreign('livestock_id')->references('id')->on('cows')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->dropColumn('frequency_type');
        });
    }

}
