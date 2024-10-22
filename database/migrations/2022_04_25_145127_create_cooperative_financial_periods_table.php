<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCooperativeFinancialPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cooperative_financial_periods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cooperative_id');
            $table->foreign('cooperative_id')->on('cooperatives')->references('id')
                ->onDelete('restrict')->onUpdate('cascade');
            $table->date('start_period');
            $table->date('end_period');
            $table->enum('type', config('enums.financial_period_types'));
            $table->double('balance_cf')->nullable();
            $table->double('balance_bf')->default(0);
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
        Schema::dropIfExists('cooperative_financial_periods');
    }
}
