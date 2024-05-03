<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_leaves', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('start_date');
            $table->date('end_date');
            $table->longText('reason')->nullable();
            $table->text('remarks')->nullable();
            $table->string('file')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->uuid('employee_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('employee_id')->references('id')->on('coop_employees')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_leaves');
    }
}
