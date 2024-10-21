<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOriginalLoanIdWhenALoanIsBoughtOff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->bigInteger('bought_off_loan_id')->unsigned()->after('bought_off_at')->nullable();
            $table->foreign('bought_off_loan_id')->references('id')->on('loans')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign('loans_bought_off_loan_id_foreign');
            $table->dropColumn('bought_off_loan_id');
        });
    }
}
