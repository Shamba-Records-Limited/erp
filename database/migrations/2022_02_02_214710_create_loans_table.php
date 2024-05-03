<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->double('amount')->default(0);
            $table->double('balance')->default(0);
            $table->string('status'); //completed, pending
            $table->uuid('farmer_id');
            $table->date('due_date');
            $table->string('mode_of_payment'); //one_off, installments, next_payment
            $table->double('interest')->default(1);
            $table->string('purpose');
            $table->foreign('farmer_id')->references('id')->on('farmers')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::dropIfExists('loans');
    }
}
