<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Providers\ED;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'system_id' => 0,
            'market_id' => $this->faker->unique()->numberBetween(100000, 999999999),
            'name' => $this->faker->word(),
            'type' => $this->faker->randomElement(['Coriolis Starport', 'Orbis Starport', 'Ocellus Starport', 'Asteroid Base', 'Mega Ship', 'Planetary Outpost', 'Planetary Port', 'Planetary Settlement']),
            'distance_to_arrival' => $this->faker->numberBetween(0, 100000),
            'allegiance' => $this->faker->randomElement(['Alliance', 'Empire', 'Federation', 'Independent']),
            'government' => $this->faker->randomElement(['Anarchy', 'Communism', 'Confederacy', 'Cooperative', 'Corporate', 'Democracy', 'Dictatorship', 'Feudal', 'Imperial', 'Patronage', 'Prison Colony', 'Theocracy']),
            'economy' => $this->faker->randomElement(['Agriculture', 'Colony', 'Extraction', 'High Tech', 'Industrial', 'Military', 'Refinery', 'Service', 'Terraforming', 'Tourism', 'None']),
            'state' => $this->faker->randomElement(['Under Attack', 'Damaged', 'Repair', '']),
            'landing_pads' => json_encode([
                'large' => $this->faker->numberBetween(0, 10),
                'medium' => $this->faker->numberBetween(0, 10),
                'small' => $this->faker->numberBetween(0, 10),
            ]),
            'faction_id' => 0,
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
