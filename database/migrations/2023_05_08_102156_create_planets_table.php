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
        Schema::create('planets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('system_id')->unsigned();
            $table->bigInteger('body_id')->unsigned();
            $table->float('ascending_node', 32);
            $table->string('atmosphere_type')->nullable();
            $table->json('atmosphere_composition')->nullable();
            $table->float('axial_tilt', 32);
            $table->json('composition')->nullable();
            $table->string('class');
            $table->float('distance_to_arrival', 32);
            $table->float('eccentricity', 32);
            $table->float('gravity', 32);
            $table->boolean('is_landable');
            $table->boolean('mass');
            $table->float('mean_anomaly', 32);
            $table->float('orbital_inclination', 32);
            $table->float('orbital_period', 32);
            $table->float('radius', 32);
            $table->float('rotation_period', 32);
            $table->float('semi_major_axis', 32);
            $table->float('surface_pressure', 32);
            $table->float('surface_temperature', 32);
            $table->boolean('tidally_locked');
            $table->string('terraforming_state');
            $table->string('volcanism');
            $table->json('parents')->nullable();

            $table->foreign('system_id')->references('id')->on('systems')->onDelete('cascade');

            $table->timestampTz('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planets');
    }
};
