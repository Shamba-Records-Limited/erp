<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("inventory_number")->unique();
            
            $table->uuid("miller_id");
            $table->foreign("miller_id")->references("id")->on("millers")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->uuid("order_id");
            $table->foreign("order_id")->references("id")->on("miller_auction_order")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->uuid("user_id");
            $table->foreign("user_id")->references("id")->on("users")
                ->onUpdate("cascade")
                ->onDelete("cascade");
            $table->timestamp("published_at")->nullable();

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
        Schema::dropIfExists('inventories');
    }
}
