<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('farmer_id');
            $table->uuid('product_id');
            $table->string('quantity');
            $table->string('status')->nullable();
            $table->date('date_collected');
            $table->uuid('agent_id')->nullable();
            $table->uuid('cooperative_id');
            $table->longText('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();
            //
            $table->foreign('farmer_id')->references('id')->on('farmers')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('agent_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collections');
    }
}
