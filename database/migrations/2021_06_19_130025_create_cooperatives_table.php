<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCooperativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('cooperatives')) {
        Schema::create('cooperatives', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("name");
            $table->string("abbreviation")->nullable();
            $table->uuid("country_code")->nullable();
            $table->string("location");
            $table->string("rate_type")->default('');
            $table->string("address");
            $table->string("email")->unique();
            $table->string("contact_details")->unique();
            $table->string("logo")->nullable();
            $table->string("currency")->default("KSH");
            $table->timestamps();
            $table->softDeletes();
        });

    }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
