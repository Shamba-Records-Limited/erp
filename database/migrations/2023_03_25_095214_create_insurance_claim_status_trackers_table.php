<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceClaimStatusTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_claim_status_trackers', function (Blueprint $table) {
            $table->id();
            $table->uuid('claim_id');
            $table->smallInteger('status');
            $table->string('comment');
            $table->timestamps();

            $table->foreign('claim_id')
                ->references('id')
                ->on('insurance_claims')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_claim_status_trackers');
    }
}
