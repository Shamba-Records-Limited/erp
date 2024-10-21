<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionQualityStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_quality_standards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cooperative_id');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->string('name');
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
        Schema::dropIfExists('collection_quality_standards');
    }
}
