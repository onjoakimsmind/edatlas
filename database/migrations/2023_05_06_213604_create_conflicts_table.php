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
        Schema::create('conflicts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('system_id')->unsigned();
            $table->string('faction1');
            $table->string('faction2');
            $table->string('faction1_stake')->nullable();
            $table->string('faction2_stake')->nullable();
            $table->integer('faction1_days_won')->default(0);
            $table->integer('faction2_days_won')->default(0);
            $table->string('war_type');
            $table->string('status')->nullable();

            $table->foreign('system_id')->references('id')->on('systems')->onDelete('cascade');

            $table->timestampTz('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conflicts');
    }
};
