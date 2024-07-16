<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_invoice_items', function (Blueprint $table) {
            $table->uuid("id")->primary();

            $table->uuid("new_invoice_id")->nullable();
            $table->foreign('new_invoice_id')->references('id')->on('new_invoices')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->uuid("item_id");
            $table->string("item_type");
            $table->float("price");
            $table->float("quantity");
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
        Schema::dropIfExists('new_invoice_items');
    }
}
