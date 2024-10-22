<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreMilledInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_milled_inventories', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("inventory_number")->unique();
            
            $table->uuid("miller_id");
            $table->foreign("miller_id")->references("id")->on("millers")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->uuid("delivery_id");
            $table->foreign("delivery_id")->references("id")->on("auction_order_delivery")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->uuid("delivery_item_id");
            $table->foreign("delivery_item_id")->references("id")->on("auction_order_delivery_item")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->uuid("user_id");
            $table->foreign("user_id")->references("id")->on("users")
                ->onUpdate("cascade")
                ->onDelete("cascade");
            $table->timestamp("published_at")->nullable();

            $table->float("quantity");
            $table->string("unit")->nullable();

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
        Schema::dropIfExists('pre_milled_inventories');
    }
}
