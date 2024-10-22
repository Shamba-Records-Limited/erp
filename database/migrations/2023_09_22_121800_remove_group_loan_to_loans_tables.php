<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveGroupLoanToLoansTables extends Migration
{

    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn('group_loan');
        });
    }

    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->smallInteger('group_loan')->after('interest')->default(0);
        });
    }
}
