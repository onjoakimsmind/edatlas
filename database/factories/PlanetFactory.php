<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Planet>
 */
class PlanetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'system_id' => 1,
            'body_id' => 1,
            'name' => $this->faker->name,
            'atmosphere_type' => $this->faker->randomElement(['Ammonia', 'Argon', 'Carbon dioxide', 'Helium', 'Hydrogen', 'Iron', 'Methane', 'Neon', 'Nitrogen', 'Oxygen', 'Rock', 'Silicate vapour', 'Sulphur dioxide', 'Water']),
            'atmosphere_composition' => $this->faker->randomElement(['Ammonia', 'Argon', 'Carbon dioxide', 'Helium', 'Hydrogen', 'Iron', 'Methane', 'Neon', 'Nitrogen', 'Oxygen', 'Rock', 'Silicate vapour', 'Sulphur dioxide', 'Water']),
            'ascending_node' => $this->faker->randomFloat(2, 0, 100),
            'axial_tilt' => $this->faker->randomFloat(2, 0, 100),
            'composition' => [
                'ice' => $this->faker->randomFloat(2, 0, 100),
                'metal' => $this->faker->randomFloat(2, 0, 100),
                'rock' => $this->faker->randomFloat(2, 0, 100),
            ],
            'class' => $this->faker->randomElement(['Ammonia world', 'Earth-like world', 'Gas giant with ammonia-based life', 'Gas giant with water-based life', 'Helium-rich gas giant', 'Helium gas giant', 'High metal content world', 'Icy body', 'Metal-rich body', 'Metal-rich gas giant', 'Rocky body', 'Rocky ice world', 'Rocky ice world with water-based life', 'Rocky ice world with ammonia-based life', 'Rocky ice world with silicate vapour geysers', 'Rocky ice world with silicate vapour geysers and water-based life', 'Rocky ice world with silicate vapour geysers and ammonia-based life', 'Water giant', 'Water giant with water-based life', 'Water giant with ammonia-based life', 'Water world']),
            'distance_to_arrival' => $this->faker->randomFloat(2, 0, 100),
            'eccentricity' => $this->faker->randomFloat(2, 0, 100),
            'gravity' => $this->faker->randomFloat(2, 0, 100),
            'is_landable' => $this->faker->boolean,
            'mass' => $this->faker->randomFloat(2, 0, 100),
            'mean_anomaly' => $this->faker->randomFloat(2, 0, 100),
            'orbital_inclination' => $this->faker->randomFloat(2, 0, 100),
            'orbital_period' => $this->faker->randomFloat(2, 0, 100),
            'radius' => $this->faker->randomFloat(2, 0, 100),
            'rotation_period' => $this->faker->randomFloat(2, 0, 100),
            'semi_major_axis' => $this->faker->randomFloat(2, 0, 100),
            'surface_pressure' => $this->faker->randomFloat(2, 0, 100),
            'surface_temperature' => $this->faker->randomFloat(2, 0, 100),
            'tidally_locked' => $this->faker->boolean,
            'terraforming_state' => $this->faker->randomElement(['Terraformable', 'Terraforming', 'Terraformed', '']),
            'volcanism' => $this->faker->randomElement(['Fumaroles', 'Geysers', 'Ice geysers', 'Lava spouts', 'Major geysers', 'Major volcanism', 'Minor geysers', 'Minor volcanism', 'No volcanism', 'Silicate vapour geysers', 'Silicate magma volcanism', 'Water geysers', 'Water magma volcanism', 'Water volcanism', 'Water volcanism (carbon dioxide)']),
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
