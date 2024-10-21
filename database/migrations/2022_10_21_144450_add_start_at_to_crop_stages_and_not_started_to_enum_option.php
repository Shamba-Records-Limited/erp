<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartAtToCropStagesAndNotStartedToEnumOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `farmer_crops` CHANGE COLUMN `status` `status` ENUM('not started','in progress', 'halted', 'completed') NOT NULL DEFAULT 'not started'");
        DB::statement("ALTER TABLE `farmer_crop_progress_trackers` CHANGE COLUMN `status` `status` ENUM('not started','in progress', 'halted', 'completed') NOT NULL DEFAULT 'not started'");
        Schema::table('farmer_crop_progress_trackers', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('stage_id');
        });
        Schema::table('farmer_crops', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('stage_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `farmer_crops` CHANGE COLUMN `status` `status` ENUM('in progress', 'halted', 'completed') NOT NULL");
        DB::statement("ALTER TABLE `farmer_crop_progress_trackers` CHANGE COLUMN `status` `status` ENUM('in progress', 'halted', 'completed') NOT NULL");
        Schema::table('farmer_crop_progress_trackers', function (Blueprint $table) {
            $table->dropColumn('start_date');
        });

        Schema::table('farmer_crops', function (Blueprint $table) {
            $table->dropColumn('start_date');
        });
    }
}
