<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid('manufactured_product_id')->nullable();
            $table->uuid("collection_id")->nullable();
            $table->uuid('farmer_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->uuid('cooperative_id')->nullable();
            $table->uuid('customer_id')->nullable();
            $table->double('amount')->default(0);
            $table->double('quantity')->default(1);
            $table->double('discount')->default(0);
            $table->string('sale_batch_number');
            $table->date('date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('manufactured_product_id')->references('id')->on('productions')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('collection_id')->references('id')->on('collections')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('farmer_id')->references('id')->on('farmers')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
