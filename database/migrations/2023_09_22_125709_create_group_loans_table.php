<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_loans', function (Blueprint $table) {
            $table->id();
            $table->double('amount');
            $table->double('balance')->default(0);
            $table->smallInteger('status')->default(\App\GroupLoan::STATUS_DISBURSED);
            $table->uuid('farmer_id');
            $table->unsignedBigInteger('group_loan_summary_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('farmer_id')->references('id')
                ->on('farmers')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('group_loan_summary_id')->references('id')
                ->on('group_loan_summaries')
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
        Schema::dropIfExists('group_loans');
    }
}
