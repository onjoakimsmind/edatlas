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
        Schema::create('rings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('system_id')->unsigned()->nullable();
            $table->bigInteger('planet_id')->unsigned()->nullable();
            $table->bigInteger('star_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('type');
            $table->string('symbol');
            $table->bigInteger('mass')->unsigned();
            $table->bigInteger('inner_radius')->unsigned();
            $table->bigInteger('outer_radius')->unsigned();

            $table->foreign('system_id')->references('id')->on('systems')->onDelete('cascade');
            $table->foreign('planet_id')->references('id')->on('planets')->onDelete('cascade')->nullable();
            $table->foreign('star_id')->references('id')->on('stars')->onDelete('cascade')->nullable();
            
            $table->timestampTz('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rings');
    }
};
