<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceProductsBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_products_benefits', function (Blueprint $table) {
            $table->id();
            $table->uuid("insurance_product_id")->nullable();
            $table->uuid("benefit_id")->nullable();
            $table->foreign('insurance_product_id')->references('id')->on('insurance_products')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('benefit_id')->references('id')->on('insurance_benefits')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_products_benefits');
    }
}
