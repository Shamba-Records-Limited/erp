<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeLivestockIdToCropCalendarStages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crop_calendar_stages', function (Blueprint $table) {
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
        Schema::table('crop_calendar_stages', function (Blueprint $table) {

            $table->dropForeign('crop_calendar_stages_livestock_id_foreign');
            $table->dropColumn(['livestock_id', 'type']);
        });
    }
}
