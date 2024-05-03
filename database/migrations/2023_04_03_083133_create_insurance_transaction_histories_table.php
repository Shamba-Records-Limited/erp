<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceTransactionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_transaction_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('subscription_id');
            $table->double('amount')->default(0);
            $table->smallInteger('type');
            $table->date('date')->default(date('Y-m-d'));
            $table->uuid('created_by');
            $table->string('comments');
            $table->uuid('cooperative_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('subscription_id')
                ->references('id')
                ->on('insurance_subscribers')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
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
        Schema::dropIfExists('insurance_transaction_histories');
    }
}
