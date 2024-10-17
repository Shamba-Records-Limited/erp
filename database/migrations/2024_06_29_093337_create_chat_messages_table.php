<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->uuid("id")->primary();

            $table->string("chat_room_id");
            $table->foreign("chat_room_id")->references("id")->on("chat_rooms");

            $table->string("sender_id");
            $table->foreign("sender_id")->references("id")->on("users");
            
            $table->string("body");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
}
