<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Faction;
use App\Models\Station;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conflict>
 */
class ConflictFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faction1 = Faction::factory()->make();
        $faction2 = Faction::factory()->make();
        $stake1 = Station::factory()->make();
        $stake2 = Station::factory()->make();
        return [
            'system_id' => 1,
            'faction1' => $faction1->name,
            'faction2' => $faction2->name,
            'faction1_stake' => $this->faker->randomElement([$stake1->name, '']),
            'faction2_stake' => $this->faker->randomElement([$stake2->name, '']),
            'faction1_days_won' => $this->faker->numberBetween(0, 10),
            'faction2_days_won' => $this->faker->numberBetween(0, 10),
            'war_type' => $this->faker->randomElement(['Civil War', 'Election', 'War']),
            'status' => $this->faker->randomElement(['Active', 'Pending', 'Retreat', 'Ceasefire', '']),
        ];
    }
}
