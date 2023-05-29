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
         Schema::create('faction_system', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('faction_id')->unsigned();
            $table->bigInteger('system_id')->unsigned();

            $table->foreign('faction_id')->references('id')->on('factions');
            $table->foreign('system_id')->references('id')->on('systems');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
