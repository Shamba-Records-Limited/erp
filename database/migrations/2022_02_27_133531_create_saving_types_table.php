<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('period')->default(1);
            $table->double('interest')->default(1);
            $table->string('type');
            $table->uuid('cooperative_id');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::dropIfExists('saving_types');
    }
}
