<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSystemTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('system_tickets', function (Blueprint $table) {
            $table->dropColumn("rejected_at");

            $table->dropForeign("system_tickets_rejected_by_id_foreign");
            $table->dropColumn("rejected_by_id");

            $table->string("number")->nullable();
            $table->string("status")->nullable()->default("draft");
            $table->timestamp("published_at")->nullable();  // to enable drafts
            $table->timestamp("confirmed_at")->nullable();
            $table->timestamp("closed_at")->nullable();

            // make columns title, description nullable
            $table->string("title")->nullable()->change();
            $table->text("description")->nullable()->change();
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
        Schema::table('system_tickets', function (Blueprint $table) {
            $table->timestamp("rejected_at")->nullable();

            $table->string("rejected_by_id")->nullable();
            $table->foreign('rejected_by_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->dropColumn("number");
            $table->dropColumn("status");
            $table->dropColumn("published_at"); // disable drafts
            $table->dropColumn("confirmed_at");
            $table->dropColumn("closed_at");

            // make columns title, description not nullable
            $table->string("title")->change();
            $table->text("description")->change();
        });
    }
}
