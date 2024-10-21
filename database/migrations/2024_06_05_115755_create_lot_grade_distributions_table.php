<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotGradeDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lot_grade_distributions', function (Blueprint $table) {
            $table->uuid("id")->primary();

            $table->string("lot_number");
            $table->foreign("lot_number")->references("lot_number")->on("lots")
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
        Schema::dropIfExists('lot_grade_distributions');
    }
}
