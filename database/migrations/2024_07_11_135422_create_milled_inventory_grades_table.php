<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMilledInventoryGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milled_inventory_grades', function (Blueprint $table) {
            $table->uuid("id")->primary();
            
            $table->uuid("milled_inventory_id");
            $table->foreign("milled_inventory_id")->references("id")->on("milled_inventories")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->uuid('product_grade_id');
            $table->foreign('product_grade_id')->references('id')->on('product_grades')
                ->onUpdate('cascade')
                ->onDelete('cascade');
                
            $table->float('quantity');
            $table->string('unit');

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
        Schema::dropIfExists('milled_inventory_grades');
    }
}
