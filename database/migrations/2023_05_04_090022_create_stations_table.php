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
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('market_id')->unsigned();
            $table->bigInteger('system_id')->unsigned();
            $table->string('name');
            $table->string('type')->nullable();
            $table->float('distance_to_arrival', 32)->default(0);
            $table->string('allegiance')->nullable();
            $table->string('government')->nullable();
            $table->string('economy')->nullable();
            $table->string('state')->nullable();
            $table->json('landing_pads');
            $table->integer('faction_id')->unsigned()->default(0);

            $table->foreign('system_id')->references('id')->on('systems')->onDelete('cascade');

            $table->timestampTz('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stations');
    }
};
