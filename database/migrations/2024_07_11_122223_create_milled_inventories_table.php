<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMilledInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milled_inventories', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("inventory_number")->unique();

            $table->float("milled_quantity");
            $table->float("waste_quantity");

            $table->uuid("miller_id");
            $table->foreign("miller_id")->references("id")->on("millers")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->string("pre_milled_inventory_id");
            $table->foreign("pre_milled_inventory_id")->references("id")->on("pre_milled_inventories")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->uuid("user_id");
            $table->foreign("user_id")->references("id")->on("users")
                ->onUpdate("cascade")
                ->onDelete("cascade");

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
        Schema::dropIfExists('milled_inventories');
    }
}
