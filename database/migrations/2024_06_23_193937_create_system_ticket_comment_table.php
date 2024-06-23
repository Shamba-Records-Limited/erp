<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemTicketCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_ticket_comment', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->unsignedBigInteger('ticket_id');
            $table->string("comment");
            $table->string("user_id");
            $table->timestamps();

            $table->foreign("ticket_id")->references("id")->on("system_tickets");
            $table->foreign("user_id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_ticket_comment');
    }
}
