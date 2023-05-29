<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator;

use App\Models\System;
use App\Models\Faction;
use App\Models\Station;
use App\Models\Market;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = app(Generator::class);
        System::factory()->count(50)->create();
        $systems = System::all();

        $systems->each(function ($system) use ($faker) {
            $factions = Faction::factory()->count(5)->make();
            $f = collect($factions)->map(function($n) use ($system) {
                $activeStates = isset($n->active_states) ? join(',',array_map(fn($f) => $f, $n->active_states)) : null;
                $pendingStates = isset($n->pending_states) ? join(',',array_map(fn($f) =>$f, $n->pending_states)) : null;
                $recoveringStates = isset($n->recovering_states) ? join(',',array_map(fn($f) => $f, $n->recovering_states)) : null;
                return [
                    'system_id' => $system->id,
                    'name' => $n->name,
                    'allegiance' => $n->allegiance,
                    'government' => $n->government,
                    'state' => $n->state,
                    'influence' => $n->influence,
                    'happiness' => $n->happiness != "" ? $n->happiness : "",
                    'active_states' => $activeStates,
                    'pending_states' => $pendingStates,
                    'recovering_states' => $recoveringStates,
                ];
            })->toArray();
            $system->factions()->createMany($f);
            $faction = Faction::where('system_id', $system->id)->orderBy('influence', 'desc')->first();
            $system->update(['faction_id' => $faction->id]);
            $system->save();

            $hasStations = $faker->boolean(50);
            if($hasStations) {
                $stationFaction = Faction::where('system_id', $system->id)->inRandomOrder()->first();
                $numStations = $faker->numberBetween(1, 5);
                $stations = Station::factory()->hasMarket(1, function (array $attributes, Station $station) {
                    return ['id' => $station->market_id];
                })->count($numStations)->create(['system_id' => $system->id, 'faction_id' => $stationFaction->id]);
            }
        });
    }
}
