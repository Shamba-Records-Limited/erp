<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('accounting_rules');
        Schema::dropIfExists('accounting_configurations');
        Schema::create('parent_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('parent_ledger_code');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });

        DB::table('parent_ledgers')->insert(
            array(
                array('name' => 'Assets', 'parent_ledger_code' => 10000),
                array('name' => 'Liabilities', 'parent_ledger_code' => 20000),
                array('name' => 'Share', 'parent_ledger_code' => 30000),
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parent_ledgers');
    }
}
