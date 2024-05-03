<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDisciplinariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_disciplinaries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_id');
            $table->date('effective_date');
            $table->integer('days')->nullable();
            $table->date('end_date')->nullable();
            $table->smallInteger('with_pay');
            $table->smallInteger('disciplinary_type');
            $table->text('reason');
            $table->smallInteger('status')->default(\App\EmployeeDisciplinary::STATUS_ACTIVE);
            $table->uuid('actioned_by');
            $table->uuid('cooperative_id');
            $table->foreign('employee_id')->references('id')
                ->on('coop_employees')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('actioned_by')->references('id')
                ->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('cooperative_id')->references('id')
                ->on('cooperatives')
                ->onUpdate('cascade')->onDelete('restrict');
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
        Schema::dropIfExists('employee_disciplinaries');
    }
}
