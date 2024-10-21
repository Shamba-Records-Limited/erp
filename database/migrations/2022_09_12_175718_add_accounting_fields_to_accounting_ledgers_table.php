<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountingFieldsToAccountingLedgersTable extends Migration
{

    private function add_new_records(){

        DB::table('accounting_ledgers')->insert(
            array(
                array('name' => 'Collections','type'=>'current','parent_ledger_id'=>1, 'ledger_code'=>10001),
                array('name' => 'Debts','type'=>'current','parent_ledger_id'=>2, 'ledger_code'=>20001),
                array('name' => 'Cash At Hand','type'=>'current','parent_ledger_id'=>1, 'ledger_code'=>10002),
                array('name' => 'Cash At Bank','type'=>'current','parent_ledger_id'=>1, 'ledger_code'=>10003),
                array('name' => 'Farmer Payments','type'=>'current','parent_ledger_id'=>2, 'ledger_code'=>20002),
                array('name' => 'Loans','type'=>'long term','parent_ledger_id'=>2, 'ledger_code'=>20003),
                array('name' => 'Loan Repayments','type'=>'long term','parent_ledger_id'=>1, 'ledger_code'=>10004),
                array('name' => 'Savings','type'=>'long term','parent_ledger_id'=>2, 'ledger_code'=>20004),
                array('name' => 'Sales','type'=>'current','parent_ledger_id'=>1, 'ledger_code'=>10005),
                array('name' => 'Sales Discounts','type'=>'current','parent_ledger_id'=>2, 'ledger_code'=>20005),
                array('name' => 'Property','type'=>'long term','parent_ledger_id'=>1, 'ledger_code'=>10006),
                array('name' => 'Purchase Payments','type'=>'current','parent_ledger_id'=>2, 'ledger_code'=>20006),
                array('name' => 'Subscriptions','type'=>'current','parent_ledger_id'=>1, 'ledger_code'=>10007),
                array('name' => 'Salaries','type'=>'current','parent_ledger_id'=>2, 'ledger_code'=>20007),
            )
        );
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(Schema::hasColumn('accounting_transactions', 'accounting_ledger_id'))
        {
            Schema::table('accounting_transactions', function (Blueprint $table) {
                $table->dropForeign('accounting_transactions_accounting_ledger_id_foreign');
                $table->dropColumn('accounting_ledger_id');
            });
        }
        \App\AccountingTransaction::truncate();
        \App\AccountingLedger::truncate();

        if(!Schema::hasColumn('accounting_transactions', 'accounting_ledger_id')) {
            Schema::table('accounting_transactions', function (Blueprint $table) {
                $table->unsignedBigInteger('accounting_ledger_id')->after('id');
                $table->foreign('accounting_ledger_id')->on('accounting_ledgers')->references('id')
                    ->onDelete('restrict')->onUpdate('cascade');
            });
        }
        Schema::table('accounting_ledgers', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_ledger_id')->after('name');
            $table->foreign('parent_ledger_id')->references('id')->on('parent_ledgers')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->integer('ledger_code')->default(10001)->after('type');
            $table->text('description')->nullable()->after('ledger_code');
        });
        $this->add_new_records();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting_ledgers', function (Blueprint $table) {
            $table->dropColumn('ledger_code');
            $table->dropColumn('description');
            $table->dropForeign(['accounting_ledgers_parent_ledger_id_foreign']);
            $table->dropColumn('parent_ledger_id');
        });
    }
}
