<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_installments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->double('amount')->default(1);
            $table->datetime('date')->default(date('Y-m-d H:i:s'));
            $table->string('status');
            $table->uuid('loan_id');
            $table->foreign('loan_id')->references('id')->on('loans')
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
        Schema::dropIfExists('loan_installments');
    }
}
