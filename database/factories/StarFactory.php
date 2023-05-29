<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use \App\Providers\ED;


class StarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $distance_to_arrival = $this->faker->randomFloat(2, 0, 100);
        $type = $this->faker->randomElement(['A', 'B', 'F', 'G', 'K', 'M', 'O', 'T', 'Y']);
        return [
            'system_id' => 1,
            'body_id' => 1,
            'name' => $this->faker->name,
            'type' => $type,
            'class' => $this->faker->randomDigit(),
            'axial_tilt' => $this->faker->randomFloat(2, 0, 100),
            'absolute_magnitude' => $this->faker->randomFloat(2, 0, 100),
            'age' => $this->faker->randomFloat(2, 0, 100),
            'ascending_node' => $this->faker->randomFloat(2, 0, 100),
            'distance_to_arrival' => $distance_to_arrival,
            'eccentricity' => $this->faker->randomFloat(2, 0, 100),
            'is_main_star' => $distance_to_arrival == 0 ? true : false,
            'is_scoopable' => ED::isScoopable($type),
            'luminosity' => $this->faker->randomFloat(2, 0, 100),
            'mean_anomaly' => $this->faker->randomFloat(2, 0, 100),
            'orbital_inclination' => $this->faker->randomFloat(2, 0, 100),
            'orbital_period' => $this->faker->randomFloat(2, 0, 100),
            'radius' => $this->faker->randomFloat(2, 0, 100),
            'rotation_period' => $this->faker->randomFloat(2, 0, 100),
            'semi_major_axis' => $this->faker->randomFloat(2, 0, 100),
            'stellar_mass' => $this->faker->randomFloat(2, 0, 100),
            'surface_temperature' => $this->faker->randomFloat(2, 0, 100),
            'periapsis' => $this->faker->randomFloat(2, 0, 100),
            'parents' => $this->parents(),
        ];
    }

    private function parents(): array | null
    {
        if($this->faker->boolean(50)) return null;
        $parents = [];
        for ($i = 0; $i < $this->faker->numberBetween(0, 4); $i++) {
            $parent = $this->faker->randomElement(['star' => $this->faker->randomDigit(), 'planet' => $this->faker->randomDigit(), 'barycenter' => $this->faker->randomDigit()]);
            $parents[] = $parent;
        }
        return $parents;
    }
}
