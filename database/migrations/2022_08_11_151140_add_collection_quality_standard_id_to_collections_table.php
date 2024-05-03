<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollectionQualityStandardIdToCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->uuid('collection_quality_standard_id')->nullable(true);
            $table->foreign('collection_quality_standard_id')->on('collection_quality_standards')->references('id')
                ->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collections', function (Blueprint $table) {
            Schema::table('collections', function (Blueprint $table) {
                $table->dropForeign('collections_collection_quality_standard_id_foreign');
                $table->dropColumn('cooperative_id');
            });
        });
    }
}
