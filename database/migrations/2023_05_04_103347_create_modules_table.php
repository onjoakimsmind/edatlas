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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('market_id')->unsigned();
            $table->string('name');
            $table->string('symbol');
            $table->string('category');
            $table->string('mount');
            $table->integer('class');
            $table->string('rating');
            $table->string('ship');
            $table->string('guidance');

            $table->foreign('market_id')->references('id')->on('markets')->onDelete('cascade');

            $table->timestampTz('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
