<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAppraisalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_appraisals', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('employee_id');
            $table->foreign('employee_id')
                ->references('id')
                ->on('coop_employees')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->smallInteger('appraisal_type');
            $table->date('effective_date');

            $table->uuid('old_position_id');
            $table->foreign('old_position_id')
                ->references('id')
                ->on('job_positions')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->uuid('new_position_id');
            $table->foreign('new_position_id')
                ->references('id')
                ->on('job_positions')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->string('old_job_group'); //level or job group
            $table->string('new_job_group');

            $table->uuid('old_department_id');
            $table->uuid('new_department_id');
            $table->foreign('old_department_id')
                ->references('id')
                ->on('coop_branch_departments')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('new_department_id')
                ->references('id')
                ->on('coop_branch_departments')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->double('old_salary');
            $table->double('new_salary');
            $table->uuid('old_employment_type_id');
            $table->uuid('new_employment_type_id');
            $table->foreign('old_employment_type_id')
                ->references('id')
                ->on('employment_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('new_employment_type_id')
                ->references('id')
                ->on('employment_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->text('comments');
            $table->uuid('actioned_by_id');
            $table->foreign('actioned_by_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->uuid('cooperative_id');
            $table->foreign('cooperative_id')
                ->references('id')
                ->on('cooperatives')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_appraisals');
    }
}
