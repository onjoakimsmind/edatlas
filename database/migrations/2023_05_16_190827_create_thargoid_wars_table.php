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
        Schema::create('thargoid_wars', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('system_id')->unsigned();
            $table->string('current_state')->default('');
            $table->integer('days_remaining')->default(0);
            $table->string('success_state')->default('');
            $table->string('failure_state')->default('');
            $table->integer('remaining_ports')->default(0);
            $table->boolean('success_reached')->default(false);
            $table->float('war_progress')->default(0);
            $table->timestampTz('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thargoid_wars');
    }
};
