<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trade_routes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('from_market_id')->unsigned();
            $table->bigInteger('to_market_id')->unsigned();
            $table->string('commodity');
            $table->integer('buy_price');
            $table->integer('sell_price');
            $table->integer('profit');
            $table->float('distance');
            $table->timestampTz('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_routes');
    }
};
