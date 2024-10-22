<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->uuid("id")->primary();

            $table->uuid("inventory_id");
            $table->foreign("inventory_id")->references("id")->on("inventories")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->uuid("product_grade_id");
            $table->foreign("product_grade_id")->references("id")->on("product_grades")
                ->onUpdate("cascade")
                ->onDelete("cascade");
            
            $table->string('name');
            $table->float("quantity");
            $table->string("unit");                
            $table->boolean('is_waste')->default(false);

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
        Schema::dropIfExists('inventory_items');
    }
}
