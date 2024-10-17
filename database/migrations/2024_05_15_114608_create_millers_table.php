<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMillersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('millers', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("name");
            $table->string("abbreviation");
            $table->string("country_code");
            $table->string("email")->unique();
            $table->string("logo")->nullable();
            $table->string("address");
            $table->string("phone_no");
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
        Schema::dropIfExists('millers');
    }
}
