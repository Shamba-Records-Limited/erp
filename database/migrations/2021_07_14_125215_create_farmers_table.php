<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmers', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("country_id")->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('set null');
            $table->string('county');
            $table->string('location');
            $table->string('id_no');
            $table->string('phone_no');
            $table->uuid("route_id")->nullable();
            $table->foreign('route_id')->references('id')->on('routes')->onUpdate('cascade')->onDelete('set null');
            $table->string('bank_account')->nullable();
            $table->uuid('bank_branch_id')->nullable();
            $table->foreign('bank_branch_id')->references('id')->on('bank_branches')->onUpdate('cascade')->onDelete('set null');
            $table->string('member_no');
            $table->string('customer_type');
            $table->string('kra');
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('farmers');
    }
}
