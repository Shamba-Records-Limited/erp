<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankIdToEmployeeBankDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_bank_details', function (Blueprint $table) {
            $table->uuid('bank_id')->after('account_number')->nullable();
            $table->foreign('bank_id')->references('id')
                ->on('banks')
                ->onUpdate('CASCADE')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_bank_details', function (Blueprint $table) {
            $table->dropForeign('employee_bank_details_bank_id_foreign');
            $table->dropColumn('bank_id');
        });
    }
}
