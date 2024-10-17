<?php

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyOptionToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            Type::hasType('enum') ?: Type::addType('enum', StringType::class);
            $table->enum('title', config('enums.titles'))->nullable()->change();
            $table->enum('gender', config('enums.genders'))->nullable()->change();
            $table->smallInteger('customer_type')->after('id')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            Type::hasType('enum') ?: Type::addType('enum', StringType::class);
            $table->enum('title', config('enums.titles'))->nullable(false)->default('Mr.')->change();
            $table->enum('gender', config('enums.genders'))->nullable(false)->default('M')->change();
            $table->dropColumn('customer_type');
        });
    }
}
