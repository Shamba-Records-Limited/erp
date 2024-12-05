<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductMillerCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_miller_carts', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('miller_id', 36)->index();
            $table->char('farmer_id', 36)->index();
            $table->char('user_id', 36)->index();
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_miller_carts');
    }
}
