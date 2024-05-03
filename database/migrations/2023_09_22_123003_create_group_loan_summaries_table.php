<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupLoanSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_loan_summaries', function (Blueprint $table) {
            $table->id();
            $table->integer('number_of_farmers')->default(1);
            $table->double('total_amount');
            $table->uuid('group_loan_type_id');
            $table->uuid('created_by');
            $table->uuid('cooperative_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('created_by')->on('users')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('group_loan_type_id')->references('id')
                ->on('group_loan_types')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('cooperative_id')->on('cooperatives')
                ->references('id')
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
        Schema::dropIfExists('group_loan_summaries');
    }
}
