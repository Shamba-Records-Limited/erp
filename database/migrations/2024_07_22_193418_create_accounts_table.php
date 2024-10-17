<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("acc_number")->unique();
            $table->string("owner_type");
            $table->uuid("owner_id");

            $table->string("credit_or_debit"); // CREDIT, DEBIT
            
            $table->string("currency")->default("KES");
            $table->float("balance")->default(0);
            // $table->boolean("is_system_account")->default("false");  // fix system account to A0000001
            $table->string("status")->default("ACTIVE");

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
        Schema::dropIfExists('accounts');
    }
}
