<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsurancePaymentModeAdjustedRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_payment_mode_adjusted_rates', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('payment_mode');
            $table->double('adjusted_rate')->default(0);
            $table->uuid('cooperative_id');
            $table->timestamps();
            $table->foreign('cooperative_id')
                ->references('id')
                ->on('cooperatives')
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
        Schema::dropIfExists('insurance_payment_mode_adjusted_rates');
    }
}
