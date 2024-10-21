<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropProductIdOnRawMaterialsTable extends Migration
{

    public function up()
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropForeign('raw_materials_product_id_foreign');
            $table->dropColumn('product_id');
        });
    }


    public function down()
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->uuid('product_id')->after('id')->nullable();
            $table->foreign('product_id')->on('products')->references('id')->onUpdate('cascade')->onDelete('set null');
        });
    }

}
