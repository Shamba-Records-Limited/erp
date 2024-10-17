<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceDependantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_dependants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('subscription_id');
            $table->string('name');
            $table->smallInteger('relationship');
            $table->string('idno');
            $table->date('dob');
            $table->smallInteger('no');
            $table->uuid('cooperative_id');
            $table->timestamps();
            $table->foreign('cooperative_id')
                ->references('id')
                ->on('cooperatives')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('subscription_id')
                ->references('id')
                ->on('insurance_subscribers')
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
        Schema::dropIfExists('insurance_dependants');
    }
}
