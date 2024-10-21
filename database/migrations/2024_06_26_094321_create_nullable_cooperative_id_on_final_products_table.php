<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNullableCooperativeIdOnFinalProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('final_products', function (Blueprint $table) {
            $table->string("cooperative_id")->nullable();
            $table->foreign("cooperative_id")->references("id")->on("cooperatives");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('final_products', function (Blueprint $table) {
            $table->dropForeign("final_products_cooperative_id_foreign");
            $table->dropColumn("cooperative_id");
        });
    }
}
