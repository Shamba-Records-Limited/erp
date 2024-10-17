<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreToProductionsTable extends Migration
{
    public function up()
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->uuid('manufacturing_store_id')->nullable()->after('expiry_date');
            $table->foreign('manufacturing_store_id')->references('id')
                ->on('manufacturing_stores')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
        });
    }

    public function down()
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropForeign('productions_manufacturing_store_id_foreign');
            $table->dropColumn('manufacturing_store_id');
        });
    }
}
