<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColsFromSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign('sales_manufactured_product_id_foreign');
            $table->dropForeign('sales_collection_id_foreign');
            $table->dropColumn([
                'manufactured_product_id',
                'collection_id',
                'amount',
                'quantity',
                'discount'
            ]);
            $table->enum('type', config('enums.sale_types'))->default('sale');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign('user_id');
            $table->dropColumn('type');

            $table->uuid('manufactured_product_id')->nullable();
            $table->uuid("collection_id")->nullable();
            $table->double('amount')->default(0);
            $table->double('quantity')->default(1);
            $table->double('discount')->default(0);
            $table->foreign('manufactured_product_id')->references('id')->on('productions')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('collection_id')->references('id')->on('collections')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }
}
