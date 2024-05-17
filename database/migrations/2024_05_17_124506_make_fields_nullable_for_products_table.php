<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;

class MakeFieldsNullableForProductsTable extends Migration
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
        Schema::table('products', function (Blueprint $table) {
            $table->double('sale_price')->default(0)->change();
            $table->double('vat')->default(0)->change();
            $table->double('threshold')->default(0)->change();
            $table->double('buying_price')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
