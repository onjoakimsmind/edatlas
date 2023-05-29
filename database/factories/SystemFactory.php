<?php

namespace Database\Factories;

use App\Models\Faction;
use App\Providers\ED;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\System>
 */
class SystemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $x = $this->faker->numberBetween(-1000, 1000);
        $y = $this->faker->numberBetween(-1000, 1000);
        $z = $this->faker->numberBetween(-1000, 1000);
        return [
            'name' => $this->faker->unique()->word(),
            'address' => $this->faker->unique()->numberBetween(100000, 999999),
            'x' => $x,
            'y' => $y,
            'z' => $z,
            'distance' => ED::distanceSol($x, $y, $z),
            'faction_id' => 0,
            'population' => $this->faker->numberBetween(0, 999999999),
            'government' => $this->faker->randomElement(['Anarchy', 'Communism', 'Confederacy', 'Cooperative', 'Corporate', 'Democracy', 'Dictatorship', 'Feudal', 'Imperial', 'Patronage', 'Prison Colony', 'Theocracy']),
            'allegiance' => $this->faker->randomElement(['Alliance', 'Empire', 'Federation', 'Independent', 'Thargoid']),
            'powers' => json_encode([$this->faker->randomElement(['Aisling Duval', 'Archon Delaine', 'Arissa Lavigny-Duval', 'Denton Patreus', 'Edmund Mahon', 'Felicia Winters', 'Li Yong-Rui', 'Pranav Antal', 'Yuri Grom', 'Zachary Hudson', 'Zemina Torval'])]),
            'pps' => $this->faker->randomElement(['Control', 'Exploited', 'Contested']),
            'security' => $this->faker->randomElement(['Low', 'Medium', 'High']),
            'economy' => $this->faker->randomElement(['Agriculture', 'Extraction', 'High Tech', 'Industrial', 'Military', 'Refinery', 'Service', 'Terraforming', 'Tourism']),
            'second_economy' => $this->faker->randomElement(['Agriculture', 'Extraction', 'High Tech', 'Industrial', 'Military', 'Refinery', 'Service', 'Terraforming', 'Tourism']),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
