<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerAuctionOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('farmer_auction_orders', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID primary key
            $table->string('batch_number')->unique(); // Unique batch number
            $table->uuid('miller_id'); // Miller ID
            $table->uuid('user_id'); // User ID
            $table->timestamp('published_at')->nullable(); // Nullable timestamp
            $table->timestamps(); // created_at and updated_at
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmer_auction_orders');
    }
}
