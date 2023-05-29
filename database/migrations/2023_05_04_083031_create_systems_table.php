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
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('address')->unsigned()->unique();
            $table->string('name')->unique();
            $table->float('x');
            $table->float('y');
            $table->float('z');
            $table->float('distance');
            $table->bigInteger('population')->unsigned()->default(0);
            $table->json('powers')->nullable();
            $table->string('pps')->nullable();
            $table->string('security')->default('Anarchy');
            $table->string('economy')->default('None');
            $table->string('second_economy')->default('None');
            $table->string('government')->default('None');
            $table->string('allegiance')->default('None');
            $table->integer('faction_id')->unsigned()->default(0);

            $table->timestampTz('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('systems');
    }
};
