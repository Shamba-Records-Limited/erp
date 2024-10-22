<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVetItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vet_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->double('quantity')->default(1);
            $table->double('bp')->default(1);
            $table->double('sp')->default(1);
            $table->uuid("unit_id")->nullable();
            $table->foreign('unit_id')->references('id')->on('units')
                ->onUpdate('cascade')->onDelete('set null');
            $table->uuid("cooperative_id")->nullable();
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')
                ->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vet_items');
    }
}
