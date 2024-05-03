<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupLoanConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_loan_configs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->smallInteger('number_of_loans_allowed')->default(1);
            $table->uuid('cooperative_id');
            $table->timestamps();
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
        Schema::dropIfExists('group_loan_configs');
    }
}
