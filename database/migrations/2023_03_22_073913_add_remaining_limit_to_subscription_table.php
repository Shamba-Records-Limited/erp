<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemainingLimitToSubscriptionTable extends Migration
{
    public function up()
    {
        Schema::table('insurance_subscribers', function (Blueprint $table) {
            $table->double('current_limit')->nullable()->after('grace_period');
        });
    }

    public function down()
    {
        Schema::table('insurance_subscribers', function (Blueprint $table) {
            $table->dropColumn('current_limit');
        });
    }
}
