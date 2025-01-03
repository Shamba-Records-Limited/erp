<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToSavingAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saving_accounts', function (Blueprint $table) {
            $table->integer('status')->after('interest')->default(\App\SavingAccount::STATUS_ACTIVE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saving_accounts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
