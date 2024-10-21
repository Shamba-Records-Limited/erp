<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalProductRawMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_product_raw_materials', function (Blueprint $table) {
            $table->uuid("id")->primary();

            $table->uuid("final_product_id");
            $table->foreign("final_product_id")->references("id")->on("final_products");
            
            $table->string("material_type");
            $table->string("material_no");
            $table->float("quantity");
            $table->string("unit");
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
        Schema::dropIfExists('final_product_raw_materials');
    }
}
