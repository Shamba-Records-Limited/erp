<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBreedIdAndToFarmerExpectedYields extends Migration
{
    public function up()
    {

        if(Schema::hasColumn('farmer_expected_yields', 'volume_indicator')){
            Schema::table('farmer_expected_yields', function (Blueprint $table) {
                $table->dropColumn('volume_indicator');
            });
        }

        Schema::table('farmer_expected_yields', function (Blueprint $table) {
            $table->dropForeign('farmer_expected_yields_livestock_id_foreign');
            $table->dropColumn('livestock_id');
            $table->uuid('livestock_breed_id')->after('crop_id')->nullable();
            $table->foreign('livestock_breed_id')->references('id')->on('breeds')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->string('volume_indicator')->after('livestock_breed_id');
        });
    }

    public function down()
    {
        Schema::table('farmer_expected_yields', function (Blueprint $table) {
            $table->dropForeign('farmer_expected_yields_livestock_breed_id_foreign');
            $table->dropColumn('livestock_breed_id');
            $table->uuid('livestock_id')->after('crop_id')->nullable();
            $table->foreign('livestock_id')->references('id')->on('cows')->onUpdate('CASCADE')->onDelete('SET NULL');
            if(!Schema::hasColumn('farmer_expected_yields', 'volume_indicator')){
                $table->string('volume_indicator')->after('livestock_breed_id');
            }
        });
    }
}
