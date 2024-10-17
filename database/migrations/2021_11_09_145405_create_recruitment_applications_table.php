<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('surname');
            $table->string('othernames');
            $table->string('phone')->nullable();
            $table->string('email');
            $table->string('area_of_residence')->nullable();
            $table->longText('qualification')->nullable();
            $table->longText('top_skills')->nullable();
            $table->string('resume');
            $table->string('cover_letter');
            $table->tinyInteger('status')->default(0);
            $table->uuid('recruitment_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('recruitment_id')->references('id')->on('recruitments')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruitment_applications');
    }
}
