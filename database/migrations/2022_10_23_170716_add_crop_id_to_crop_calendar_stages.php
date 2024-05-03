<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCropIdToCropCalendarStages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crop_calendar_stages', function (Blueprint $table) {
            $table->uuid('crop_id')->after('period_measure')->nullable();
            $table->foreign('crop_id')->references('id')->on('crops')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crop_calendar_stages', function (Blueprint $table) {
            $table->dropForeign('crop_calendar_stages_crop_id_foreign');
            $table->dropColumn('crop_id');
        });
    }
}
