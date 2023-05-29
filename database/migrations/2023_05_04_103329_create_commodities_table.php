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
        Schema::create('commodities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('market_id')->unsigned();
            $table->string('name');
            $table->string('symbol');
            $table->integer('buy_price');
            $table->integer('sell_price');
            $table->integer('mean_price');
            $table->integer('demand');
            $table->integer('stock');
            $table->integer('demand_bracket');
            $table->integer('stock_bracket');

            $table->foreign('market_id')->references('id')->on('markets')->onDelete('cascade');
            $table->unique(['market_id', 'symbol']);
            $table->timestampTz('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodities');
    }
};
