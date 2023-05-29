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
        Schema::create('faction_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('faction_id')->unsigned();
            $table->bigInteger('system_id')->unsigned();
            $table->float('influence');

            $table->foreign('faction_id')->references('id')->on('factions')->onDelete('cascade');
            $table->foreign('system_id')->references('id')->on('systems')->onDelete('cascade');

            $table->timestampTz('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faction_histories');
    }
};
