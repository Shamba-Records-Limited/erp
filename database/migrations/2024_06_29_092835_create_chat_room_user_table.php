<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatRoomUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_room_user', function (Blueprint $table) {
            $table->uuid("id")->primary();

            $table->string("user_id");
            $table->foreign("user_id")->references("id")->on("users");

            $table->string("chat_room_id");
            $table->foreign("chat_room_id")->references("id")->on("chat_rooms");

            $table->boolean("is_admited")->default(false);

            $table->boolean("is_admin")->default(false);
            $table->boolean("is_banned")->default(false);
            $table->boolean("is_muted")->default(false);
            $table->boolean("is_deleted")->default(false);
            $table->boolean("is_archived")->default(false);
            $table->boolean("is_hidden")->default(false);
            $table->boolean("is_pinned")->default(false);

            $table->timestamp("last_read_at")->nullable();


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
        Schema::dropIfExists('chat_room_user');
    }
}
