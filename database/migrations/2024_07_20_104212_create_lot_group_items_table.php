<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotGroupItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lot_group_items', function (Blueprint $table) {
            $table->uuid("id")->primary();

            $table->uuid("lot_group_id");
            $table->foreign('lot_group_id')->references('id')->on('lot_groups');

            $table->string("lot_number");
            $table->foreign("lot_number")->references("lot_number")->on("lots");
            
            $table->timestamps();

            $table->unique(['lot_group_id', 'lot_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lot_group_items');
    }
}
