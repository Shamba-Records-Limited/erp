<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanApplicationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_application_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->smallInteger('has_farm_tools')->default(0);
            $table->smallInteger('has_land')->default(0);
            $table->smallInteger('has_livestock')->default(0);
            $table->double('original_rate');
            $table->double('rate_applied');
            $table->double('wallet_balance');
            $table->double('average_cash_flow');
            $table->double('pending_payments');
            $table->double('limit')->default(0);
            $table->string('supporting_document')->nullable();
            $table->uuid('cooperative_id');
            $table->timestamps();

            $table->foreign('loan_id')->references('id')
                ->on('loans')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('cooperative_id')->references('id')
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
        Schema::dropIfExists('loan_application_details');
    }
}
