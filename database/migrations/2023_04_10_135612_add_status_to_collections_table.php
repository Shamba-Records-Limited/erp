<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToCollectionsTable extends Migration
{

    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->smallInteger('submission_status')->default(\App\Collection::SUBMISSION_STATUS_APPROVED)->after('status');
        });
    }

    public function down()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('submission_status');
        });
    }
}
