<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVetsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vets_vets_items', function (Blueprint $table) {
            $table->uuid("vet_id")->nullable();
            $table->foreign('vet_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');
            $table->uuid("vet_item_id")->nullable();
            $table->foreign('vet_item_id')->references('id')->on('vet_items')
                ->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('vets_vets_items');
    }
}
