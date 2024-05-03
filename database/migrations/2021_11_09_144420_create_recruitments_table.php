<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('role');
            $table->longText('description')->nullable();
            $table->longText('desired_skills')->nullable();
            $table->longText('qualifications')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('salary_range')->nullable();
            $table->string('location')->nullable();
            $table->string('file')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->dateTime('end_date');
            $table->uuid('cooperative_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruitments');
    }
}
