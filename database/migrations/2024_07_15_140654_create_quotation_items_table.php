<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->uuid("id")->primary();

            $table->uuid("quotation_id")->nullable();
            $table->foreign('quotation_id')->references('id')->on('quotations')
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
        Schema::dropIfExists('quotation_items');
    }
}
