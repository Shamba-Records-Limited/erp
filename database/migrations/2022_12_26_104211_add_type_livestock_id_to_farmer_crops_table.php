<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeLivestockIdToFarmerCropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmer_crops', function (Blueprint $table) {
            $table->dropForeign('farmer_crops_crop_id_foreign');
            $table->uuid('crop_id')->nullable()->change();
            $table->foreign('crop_id')->references('id')->on('crops')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->integer('type')->after('id')->default(1);
            $table->uuid('livestock_id')->after('crop_id')->nullable();
            $table->foreign('livestock_id')->references('id')->on('cows')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farmer_crops', function (Blueprint $table) {
            $table->dropForeign('farmer_crops_livestock_id_foreign');
            $table->dropColumn(['livestock_id', 'type']);
        });
    }
}
