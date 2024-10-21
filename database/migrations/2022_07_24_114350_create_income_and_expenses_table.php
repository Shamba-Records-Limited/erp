<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomeAndExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_and_expenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->double('income')->nullable();
            $table->double('expense')->nullable();
            $table->date('date')->default(\Carbon\Carbon::now()->format('Y-m-d'));
            $table->string('particulars');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->uuid('cooperative_id');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::dropIfExists('income_and_expenses');
    }
}
