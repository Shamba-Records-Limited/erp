<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLoansAndSavingsTable extends Migration
{
    public function up()
    {
        try {
            Schema::table('saving_installments', function (Blueprint $table) {
                $table->dropForeign('saving_installments_saving_id_foreign');
                $table->dropColumn('saving_id');
            });

            Schema::table('saving_accounts', function (Blueprint $table) {
                $table->dropColumn('id');
            });

            Schema::table('saving_accounts', function (Blueprint $table) {
                $table->bigIncrements('id')->first();
            });

            Schema::table('saving_installments', function (Blueprint $table) {
                $table->bigInteger('saving_id')->unsigned()->after('id')->nullable();
                $table->foreign('saving_id')->references('id')->on('saving_accounts')
                    ->onUpdate('CASCADE')->onDelete('SET NULL');
            });


            Schema::table('loan_installments', function (Blueprint $table) {
                $table->dropForeign('loan_installments_loan_id_foreign');
                $table->dropColumn('loan_id');
            });

            Schema::table('loan_payment_histories', function (Blueprint $table) {
                $table->dropForeign('loan_payment_histories_loan_id_foreign');
                $table->dropColumn('loan_id');
            });

            Schema::table('loan_repayments', function (Blueprint $table) {
                $table->dropForeign('loan_repayments_loan_id_foreign');
                $table->dropColumn('loan_id');
            });

            Schema::table('loans', function (Blueprint $table) {
                $table->dropColumn('id');
            });

            Schema::table('loans', function (Blueprint $table) {
                $table->bigIncrements('id')->first();
            });


            //create the new columns
            Schema::table('loan_installments', function (Blueprint $table) {
                $table->bigInteger('loan_id')->unsigned()->after('id')->nullable();
                $table->foreign('loan_id')->references('id')->on('loans')->onUpdate('CASCADE')->onDelete('SET NULL');
            });


            Schema::table('loan_repayments', function (Blueprint $table) {
                $table->bigInteger('loan_id')->unsigned()->after('id')->nullable();
                $table->foreign('loan_id')->references('id')->on('loans')->onUpdate('CASCADE')->onDelete('SET NULL');
            });

            Schema::table('loan_payment_histories', function (Blueprint $table) {
                $table->bigInteger('loan_id')->unsigned()->after('id')->nullable();
                $table->foreign('loan_id')->references('id')->on('loans')->onUpdate('CASCADE')->onDelete('SET NULL');
            });

        } catch (Exception $ex) {
            DB::rollBack();;
            Log::error("Exception while running migration: " . $ex->getMessage());
            echo "Error: " . $ex->getMessage();
        }

    }


    public function down()
    {
        try {
            Schema::table('saving_installments', function (Blueprint $table) {
                $table->dropForeign('saving_installments_saving_id_foreign');
                $table->dropColumn('saving_id');
            });

            Schema::table('saving_accounts', function (Blueprint $table) {
                $table->dropColumn('id');
            });

            Schema::table('saving_accounts', function (Blueprint $table) {
                $table->bigIncrements('id')->first();
            });

            Schema::table('saving_installments', function (Blueprint $table) {
                $table->bigInteger('saving_id')->unsigned()->after('id')->nullable();
                $table->foreign('saving_id')->references('id')->on('saving_accounts')
                    ->onUpdate('CASCADE')->onDelete('SET NULL');
            });


            Schema::table('loan_installments', function (Blueprint $table) {
                $table->dropForeign('loan_installments_loan_id_foreign');
                $table->dropColumn('loan_id');
            });

            Schema::table('loan_payment_histories', function (Blueprint $table) {
                $table->dropForeign('loan_payment_histories_loan_id_foreign');
                $table->dropColumn('loan_id');
            });

            Schema::table('loan_repayments', function (Blueprint $table) {
                $table->dropForeign('loan_repayments_loan_id_foreign');
                $table->dropColumn('loan_id');
            });

            Schema::table('loans', function (Blueprint $table) {
                $table->dropColumn('id');
            });

            Schema::table('loans', function (Blueprint $table) {
                $table->bigIncrements('id')->first();
            });


            //create the new columns
            Schema::table('loan_installments', function (Blueprint $table) {
                $table->bigInteger('loan_id')->unsigned()->after('id')->nullable();
                $table->foreign('loan_id')->references('id')->on('loans')->onUpdate('CASCADE')->onDelete('SET NULL');
            });


            Schema::table('loan_repayments', function (Blueprint $table) {
                $table->bigInteger('loan_id')->unsigned()->after('id')->nullable();
                $table->foreign('loan_id')->references('id')->on('loans')->onUpdate('CASCADE')->onDelete('SET NULL');
            });

            Schema::table('loan_payment_histories', function (Blueprint $table) {
                $table->bigInteger('loan_id')->unsigned()->after('id')->nullable();
                $table->foreign('loan_id')->references('id')->on('loans')->onUpdate('CASCADE')->onDelete('SET NULL');
            });
        } catch (Exception $ex) {
            Log::error("Exception while running migration: " . $ex->getMessage());
            echo "Error: " . $ex->getMessage();
        }

    }
}
