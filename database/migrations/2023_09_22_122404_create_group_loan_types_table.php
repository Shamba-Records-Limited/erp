<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupLoanTypesTable extends Migration
{

    public function up()
    {
        Schema::create('group_loan_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('created_by');
            $table->uuid('cooperative_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('created_by')->on('users')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('cooperative_id')->on('cooperatives')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_loan_types');
    }
}
