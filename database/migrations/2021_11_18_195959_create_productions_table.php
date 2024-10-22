<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('final_product_id');
            $table->double('purchases', 13,4);//
            $table->double('other_expenses', 13,4)->nullable();//
            $table->string('expense_description')->nullable();//
            $table->string('quantity');
            $table->string('unit_id')->nullable();
            $table->string('profits_expected')->nullable();//
            $table->string('final_selling_price')->nullable();
            $table->string('profits_made')->nullable();//
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('final_product_id')->references('id')->on('final_products')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('unit_id')->references('id')->on('units')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productions');
    }
}
