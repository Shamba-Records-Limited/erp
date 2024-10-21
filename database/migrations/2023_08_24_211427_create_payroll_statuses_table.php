<?php

use App\PayrollStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('status')->default(PayrollStatus::STATUS_PENDING);
            $table->uuid('payroll_id');
            $table->uuid('created_by');
            $table->uuid('updated_by')->nullable();
            $table->uuid('cooperative_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('payroll_id')->on('payrolls')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('created_by')->on('users')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('updated_by')->on('users')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('set null');
            $table->foreign('cooperative_id')->on('cooperatives')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll_statuses');
    }
}
