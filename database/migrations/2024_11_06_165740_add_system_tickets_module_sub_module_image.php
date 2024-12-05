<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSystemTicketsModuleSubModuleImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_tickets', function (Blueprint $table) {
            // Check and add the 'module' column if it does not exist
            if (!Schema::hasColumn('system_tickets', 'module')) {
                $table->string('module')->nullable();
            }
            
            // Check and add the 'submodule' column if it does not exist
            if (!Schema::hasColumn('system_tickets', 'submodule')) {
                $table->string('submodule')->nullable();
            }
            
            // Check and add the 'link' column if it does not exist
            if (!Schema::hasColumn('system_tickets', 'link')) {
                $table->string('link')->nullable();
            }
            
            // Check and add the 'image' column if it does not exist
            if (!Schema::hasColumn('system_tickets', 'image')) {
                $table->string('image')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_tickets', function (Blueprint $table) {
            // Drop columns only if they exist
            if (Schema::hasColumn('system_tickets', 'module')) {
                $table->dropColumn('module');
            }
            if (Schema::hasColumn('system_tickets', 'submodule')) {
                $table->dropColumn('submodule');
            }
            if (Schema::hasColumn('system_tickets', 'link')) {
                $table->dropColumn('link');
            }
            if (Schema::hasColumn('system_tickets', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
}
