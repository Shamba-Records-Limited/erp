<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAmountLengthInTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
             // Change the length of the 'amount' field to accommodate larger values
             $table->decimal('amount', 20, 2)->change(); // Example: 20 digits in total, 2 after the decimal point
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
             // Revert the 'amount' field to its previous size (adjust as needed)
             $table->decimal('amount', 10, 2)->change(); // Example: original size of 10 digits in total, 2 after the decimal point
        });
    }
}
