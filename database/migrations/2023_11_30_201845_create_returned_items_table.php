<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnedItemsTable extends Migration
{
    public function up()
    {
        Schema::create('returned_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sale_id');
            $table->uuid('collection_id')->nullable();
            $table->uuid('manufactured_product_id')->nullable();
            $table->double('quantity');
            $table->double('amount');
            $table->date('date');
            $table->text('notes');
            $table->uuid('served_by_id');
            $table->uuid('cooperative_id');
            $table->timestamps();

            $table->foreign('manufactured_product_id')->references('id')->on('productions')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('collection_id')->references('id')->on('collections')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('sale_id')->references('id')->on('sales')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('served_by_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    public function down()
    {
        Schema::dropIfExists('returned_items');
    }
}
