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
        Schema::create('stars', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('system_id')->unsigned();
            $table->bigInteger('body_id')->unsigned();
            $table->string('name');
            $table->float('axial_tilt', 32);
            $table->string('class');
            $table->float('absolute_magnitude', 32);
            $table->bigInteger('age');
            $table->float('ascending_node');
            $table->float('distance_to_arrival', 32);
            $table->float('eccentricity', 32);
            $table->boolean('is_main_star');
            $table->boolean('is_scoopable');
            $table->string('luminosity');
            $table->float('mean_anomaly', 32);
            $table->float('orbital_inclination', 32);
            $table->float('orbital_period', 32);
            $table->float('radius', 32);
            $table->float('rotation_period', 32);
            $table->float('semi_major_axis', 32);
            $table->float('stellar_mass', 32);
            $table->float('surface_temperature', 32);
            $table->string('type');
            $table->float('periapsis', 32);
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
        Schema::dropIfExists('stars');
    }
};
