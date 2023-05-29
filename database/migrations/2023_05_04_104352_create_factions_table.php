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
        Schema::create('factions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('system_id')->unsigned();
            $table->string('name');
            $table->string('allegiance');
            $table->string('government');
            $table->string('state')->default('None');
            $table->float('influence');
            $table->string('happiness');
            $table->string('active_states')->nullable();
            $table->string('pending_states')->nullable();
            $table->string('recovering_states')->nullable();

            $table->foreign('system_id')->references('id')->on('systems')->onDelete('cascade');
            $table->timestampTz('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factions');
    }
};
