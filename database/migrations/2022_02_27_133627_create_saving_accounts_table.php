<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->double('amount')->default(1);
            $table->date('date_started');
            $table->date('maturity_date');
            $table->double('interest');
            $table->uuid('farmer_id');
            $table->uuid('saving_type_id');
            $table->foreign('farmer_id')->references('id')->on('farmers')->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('saving_type_id')->references('id')->on('saving_types')
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
        Schema::dropIfExists('saving_accounts');
    }
}
