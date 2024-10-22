<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMainProductToCooperative extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cooperatives', function (Blueprint $table) {
            //
            $table->uuid("main_product_id")->nullable();
            $table->foreign('main_product_id')->references('id')->on('products')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cooperatives', function (Blueprint $table) {
            //
            $table->dropForeign("cooperatives_main_product_id_foreign");
            $table->dropColumn("main_product_id");
        });
    }
}
