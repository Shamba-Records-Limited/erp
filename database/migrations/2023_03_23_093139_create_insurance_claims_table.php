<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_claims', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('subscription_id');
            $table->double('amount');
            $table->smallInteger('status')->default(\App\InsuranceClaim::STATUS_PENDING);
            $table->uuid('dependant_id')->nullable();
            $table->text('description')->nullable();
            $table->uuid('cooperative_id');

            $table->foreign('dependant_id')
                ->references('id')
                ->on('insurance_dependants')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('cooperative_id')
                ->references('id')
                ->on('cooperatives')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_claims');
    }
}
