<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_tickets', function (Blueprint $table) {
            $table->id();

            $table->string("title");
            $table->string("description");
            $table->json("labels")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->string("created_by_id");
            $table->string("solved_by_id")->nullable();
            $table->timestamp("solved_at")->nullable();
            $table->string("rejected_by_id")->nullable();
            $table->timestamp("rejected_at")->nullable();


            $table->foreign('created_by_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('solved_by_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('rejected_by_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_tickets');
    }
}
