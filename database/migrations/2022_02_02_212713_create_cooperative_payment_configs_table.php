<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCooperativePaymentConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cooperative_payment_configs', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("shortcode");
            $table->string("name");
            $table->string("type");
            $table->string("consumer_key");
            $table->string("consumer_secret");
            $table->string("passkey");
            $table->string("initiator_name");
            $table->string("initiator_pass");
            $table->string("status");
            $table->uuid("cooperative_id");
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::dropIfExists('cooperative_payment_configs');
    }
}
