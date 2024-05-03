<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinkProductToCropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crops', function (Blueprint $table) {
            $table->dropColumn(['name','expected_yields']);
            $table->uuid('product_id')->after('id')->nullable();
            $table->foreign('product_id')->on('products')->references('id')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crops', function (Blueprint $table) {
            $table->dropForeign('crops_product_id_foreign');
            $table->dropColumn('product_id');
            $table->string('name')->after('id')->nullable();
            $table->string('expected_yields')->after('variety')->default('0');
        });
    }
}
