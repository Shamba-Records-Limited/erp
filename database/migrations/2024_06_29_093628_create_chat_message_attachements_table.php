<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessageAttachementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_message_attachements', function (Blueprint $table) {
            $table->uuid("id")->primary();

            $table->uuid("chat_message_id");
            $table->foreign("chat_message_id")->references("id")->on("chat_messages");

            $table->string("file_name");
            $table->string("file_path");
            $table->string("file_size");
            $table->string("file_extension");
            $table->string("file_mime_type");

            $table->string("file_hash");
            $table->string("file_hash_type");

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
        Schema::dropIfExists('chat_message_attachements');
    }
}
