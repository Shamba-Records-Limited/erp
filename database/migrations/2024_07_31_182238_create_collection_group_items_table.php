<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionGroupItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_group_items', function (Blueprint $table) {
            $table->uuid("id")->primary();

            $table->uuid("collection_group_id");
            $table->foreign('collection_group_id')->references('id')->on('collection_groups');

            $table->uuid("collection_id");
            $table->foreign("collection_id")->references("id")->on("collections");
            
            $table->timestamps();

            $table->unique(['collection_group_id', 'collection_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_group_items');
    }
}
