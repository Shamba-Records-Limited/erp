<?php

use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitIdAndChangeExpectedYieldDataType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }
        Schema::table('crops', function (Blueprint $table) {
            $table->uuid('farm_unit_id')->after('variety')->nullable();
            $table->foreign('farm_unit_id')->references('id')->on('farm_units')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->double('expected_yields')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crops', function (Blueprint $table) {
            $table->dropForeign('crops_farm_unit_id_foreign');
            $table->dropColumn('farm_unit_id');
            $table->string('expected_yields')->change();
        });
    }
}
