<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCooperativePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cooperative_properties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cooperative_id');
            $table->foreign('cooperative_id')->on('cooperatives')->references('id')
                ->onDelete('restrict')->onUpdate('cascade');
            $table->string('property');
            $table->string('type');
            $table->double('value')->default(0);
            $table->double('deprecation_rate_pa')->default(0);
            $table->double('selling_price')->nullable();
            $table->double('buying_price')->default(0);
            $table->string('documents')->nullable();
            $table->enum('status', config('enums.property_status'));
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
        Schema::dropIfExists('cooperative_properties');
    }
}
