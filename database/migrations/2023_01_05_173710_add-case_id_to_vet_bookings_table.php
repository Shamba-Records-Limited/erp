<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCaseIdToVetBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vet_bookings', function (Blueprint $table) {
            $table->uuid('reported_case_id')->after('vet_id')->nullable();
            $table->foreign('reported_case_id')->on('reported_cases')->references('id')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vet_bookings', function (Blueprint $table) {
            $table->dropForeign('vet_bookings_reported_case_id_foreign');
            $table->dropColumn('reported_case_id');
        });
    }
}
