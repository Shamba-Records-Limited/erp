<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulkPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('bulk_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('batch');
            $table->double('total_amount');
            $table->uuid('created_by_id');
            $table->smallInteger('mode');
            $table->smallInteger('status');
            $table->foreign('created_by_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->uuid('cooperative_id');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bulk_payments');
    }
}
