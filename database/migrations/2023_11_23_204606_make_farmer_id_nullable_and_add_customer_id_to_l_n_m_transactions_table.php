<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeFarmerIdNullableAndAddCustomerIdToLNMTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('l_n_m_transactions', function (Blueprint $table) {
            $table->uuid('farmer_id')->nullable()->change();
            $table->uuid('customer_id')->nullable()->after('farmer_id');
                $table->foreign('customer_id')
                    ->references('id')
                    ->on('customers')
                    ->onUpdate('CASCADE')
                    ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('l_n_m_transactions', function (Blueprint $table) {
            $table->dropForeign('l_n_m_transactions_customer_id_foreign');
            $table->dropColumn('customer_id');
        });
    }
}
