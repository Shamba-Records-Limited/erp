<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMillerIdToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
                $table->uuid('miller_id')->nullable()->after('quantity'); // Add the miller_id column
                $table->foreign('miller_id')->references('id')->on('millers')->onDelete('set null'); // Define the foreign key
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['miller_id']); // Remove the foreign key constraint
            $table->dropColumn('miller_id'); // Drop the column
        });
    }
}
