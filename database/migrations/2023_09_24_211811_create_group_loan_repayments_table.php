<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupLoanRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_loan_repayments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('amount');
            $table->smallInteger('status')->default(\App\GroupLoanRepayment::STATUS_INITIATED);
            $table->uuid('initiated_by_id');
            $table->unsignedBigInteger('group_loan_id');
            $table->smallInteger('source');
            $table->uuid('cooperative_id');
            $table->timestamps();
            $table->foreign('group_loan_id')->references('id')
                ->on('group_loans')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('initiated_by_id')->references('id')
                ->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('cooperative_id')->references('id')
                ->on('cooperatives')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_loan_repayments');
    }
}
