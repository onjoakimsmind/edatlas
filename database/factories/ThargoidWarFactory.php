<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ThargoidWar>
 */
class ThargoidWarFactory extends Factory
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
            'current_state' => $this->faker->randomElement(['Maelstrom', 'Thargoid Controlled', 'Thargoid Recovery', 'Thargoid Incursion', 'Thargoid Harvest', 'Thargoid Probing', '']),
            'days_remaining' => $this->faker->numberBetween(0, 100),
            'success_state' => $this->faker->randomElement(['Thargoid Controlled', 'Thargoid Recovery', 'Thargoid Incursion', 'Thargoid Harvest', 'Thargoid Probing', '']),
            'failure_state' => $this->faker->randomElement(['Thargoid Controlled', 'Thargoid Recovery', 'Thargoid Incursion', 'Thargoid Harvest', 'Thargoid Probing', '']),
            'remaining_ports' => $this->faker->numberBetween(0, 10),
            'success_reached' => $this->faker->boolean(),
            'war_progress' => $this->faker->randomFloat(0, 1, 1),
        ];
    }
}
