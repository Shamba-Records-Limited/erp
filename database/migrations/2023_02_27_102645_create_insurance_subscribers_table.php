<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_subscribers', function (Blueprint $table) {
            $table->id();
            $table->uuid('farmer_id');
            $table->uuid('insurance_valuation_id')->nullable();
            $table->uuid('insurance_product_id');
            $table->smallInteger('status');
            $table->double('interest')->default(0);
            $table->smallInteger('payment_mode');
            $table->double('period')->default(1);
            $table->date('expiry_date');
            $table->double('amount_claimed')->default(0);
            $table->double('adjusted_premium')->default(0);
            $table->double('penalty')->default(0);
            $table->smallInteger('grace_period')->default(0);
            $table->uuid('cooperative_id');
            $table->timestamps();

            $table->foreign('cooperative_id')
                ->references('id')
                ->on('cooperatives')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');

            $table->foreign('insurance_product_id')
                ->references('id')
                ->on('insurance_products')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');

            $table->foreign('farmer_id')
                ->references('id')
                ->on('farmers')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');

            $table->foreign('insurance_valuation_id')
                ->references('id')
                ->on('insurance_valuations')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_subscribers');
    }
}
