<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLNMTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('l_n_m_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('merchant_request_id')->index();
            $table->string('checkout_request_id')->index();
            $table->string('result_code');
            $table->string('result_description');
            $table->double('amount');
            $table->string('receipt')->nullable()->index();
            $table->dateTime('transaction_date')->nullable();
            $table->string('phone_number')->index();
            $table->smallInteger('status')->index();
            $table->uuid('farmer_id');
            $table->uuid('cooperative_id');
            $table->string('model_name');
            $table->dateTime('created_at')->default(\Carbon\Carbon::now()->format('Y-m-d H:i:s'));
            $table->dateTime('updated_at')->default(\Carbon\Carbon::now()->format('Y-m-d H:i:s'));

            $table->foreign('farmer_id')->references('id')
                ->on('farmers')
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
        Schema::dropIfExists('l_n_m_transactions');
    }
}
