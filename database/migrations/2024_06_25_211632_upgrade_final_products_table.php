<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpgradeFinalProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('final_products', function (Blueprint $table) {

            $table->string("miller_id")->nullable();
            $table->foreign("miller_id")->references("id")->on("millers");

            $table->timestamp("published_at")->nullable();

            $table->string("user_id");  // publisher
            $table->foreign("user_id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('final_products', function (Blueprint $table) {
            $table->dropForeign("final_products_miller_id_foreign");
            $table->dropColumn("miller_id");

            $table->dropForeign("final_products_user_id_foreign");
            $table->dropColumn("user_id");
        });
    }
}
