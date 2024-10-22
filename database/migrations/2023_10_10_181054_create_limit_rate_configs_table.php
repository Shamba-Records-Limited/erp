<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLimitRateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('limit_rate_configs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->double('rate');
            $table->smallInteger('needs_approval');
            $table->double('limit_for_approval')->nullable();
            $table->uuid('cooperative_id');
            $table->foreign('cooperative_id')->references('id')
                ->on('cooperatives')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
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
        Schema::dropIfExists('limit_rate_configs');
    }
}
