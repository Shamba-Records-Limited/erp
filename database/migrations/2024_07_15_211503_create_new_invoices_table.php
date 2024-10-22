<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_invoices', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("invoice_number");

            $table->uuid("quotation_id")->nullable();
            $table->foreign('quotation_id')->references('id')->on('quotations')
                ->onUpdate('cascade')
                ->onDelete('cascade');



            $table->uuid("customer_id")->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->uuid("miller_id")->nullable();
            $table->foreign('miller_id')->references('id')->on('millers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->uuid("user_id")->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->timestamp("published_at")->nullable();
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
        Schema::dropIfExists('new_invoices');
    }
}
