<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->uuid("id")->primary();

            $table->boolean("is_group")->default(false);
            $table->string("group_name")->nullable();   // set when is_group is true
            $table->string("group_code")->nullable();
            $table->string("group_password")->nullable();
            
            $table->string("created_by");
            $table->foreign("created_by")->references("id")->on("users");

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
        Schema::dropIfExists('chat_rooms');
    }
}
