<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToVetServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vet_services', function (Blueprint $table) {
            $table->enum('type', config('enums.vet_service_types'))->after('name')->default('Vet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vet_services', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
