<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faction>
 */
class FactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private $astates = ['Boom', 'Bust', 'Civil Unrest', 'Civil War', 'Expansion', 'Famine', 'Investment', 'Lockdown', 'Outbreak', 'Retreat', 'War'];

    public function definition(): array
    {
        return [
            'system_id' => 0,
            'name' => $this->faker->word(),
            'allegiance' => $this->faker->randomElement(['Alliance', 'Empire', 'Federation', 'Independent']),
            'government' => $this->faker->randomElement(['Anarchy', 'Communism', 'Confederacy', 'Cooperative', 'Corporate', 'Democracy', 'Dictatorship', 'Feudal', 'Imperial', 'Patronage', 'Prison Colony', 'Theocracy']),
            'influence' => $this->faker->randomFloat(0, 1, 1),
            'state' => $this->faker->randomElement($this->astates),
            'happiness' => $this->faker->randomElement(['Despondent', 'Unhappy', 'Discontented', 'Happy', 'Elated']),
            'active_states' => $this->randomStates(),
            'pending_states' => $this->randomStates(),
            'recovering_states' => $this->randomStates(),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    private function randomStates()
    {
        $rand = rand(0,3);
        if($rand == 0) {
            return null;
        }
        return $this->faker->randomElements($this->astates, $rand);
    }
}
