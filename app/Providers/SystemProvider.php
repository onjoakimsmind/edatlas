<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\System;
use App\Models\Faction;
use App\Models\FactionHistory;

class SystemProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public static function store($payload)
    {
        $system = System::firstOrNew(['address' => $payload->system->address], [
            'name' => $payload->system->name,
            'address' => $payload->system->address,
            'x' => $payload->system->location->x,
            'y' => $payload->system->location->y,
            'z' => $payload->system->location->z,
            'distance' => ED::distanceSol($payload->system->location->x, $payload->system->location->y, $payload->system->location->z),
        ]);

        if(property_exists($payload->system, 'allegiance')) $system->allegiance = $payload->system->allegiance != "" ? $payload->system->allegiance : "None" ;
        if(property_exists($payload->system, 'economy')) $system->economy = ED::$economy[$payload->system->economy];
        if(property_exists($payload->system, 'second_economy')) $system->second_economy = ED::$economy[$payload->system->second_economy];
        if(property_exists($payload->system, 'government')) $system->government = ED::$government[$payload->system->government];
        if(property_exists($payload->system, 'population')) $system->population = $payload->system->population;
        if(property_exists($payload->system, 'security')) $system->security = ED::$security[$payload->system->security];
        if(property_exists($payload->system, 'faction')) $system->faction_id = 0;
        if(property_exists($payload->system, 'powers')) $system->powers = json_encode($payload->system->powers);
        if(property_exists($payload->system, 'pps')) $system->pps = $payload->system->pps;

        $system->save();

        if(property_exists($payload, 'factions') && $payload->factions != null) {
            $factionIds = [];
            /*$factions = array_map(function ($n) use ($system) {
                $faction = new Faction;
                $activeStates = isset($n->active_states) ? join(',',array_map(fn($f) => ED::$factionState[$f], $n->active_states)) : null;
                $pendingStates = isset($n->pending_states) ? join(',',array_map(fn($f) => ED::$factionState[$f], $n->pending_states)) : null;
                $recoveringStates = isset($n->recovering_states) ? join(',',array_map(fn($f) => ED::$factionState[$f], $n->recovering_states)) : null;
                return [
                    'system_id' => $system->id,
                    'name' => $n->name,
                    'allegiance' => $n->allegiance,
                    'government' => $n->government,
                    'state' => ED::$factionState[$n->state],
                    'influence' => $n->influence,
                    'happiness' => $n->happiness != "" ? ED::$happiness[$n->happiness] : "",
                    'active_states' => $activeStates ,
                    'pending_states' => $pendingStates,
                    'recovering_states' => $recoveringStates,
                ];

            }, $payload->factions);*/

            foreach($payload->factions as $key => $n) {
                $activeStates = isset($n->active_states) ? join(',',array_map(fn($f) => ED::$factionState[$f], $n->active_states)) : null;
                $pendingStates = isset($n->pending_states) ? join(',',array_map(fn($f) => ED::$factionState[$f], $n->pending_states)) : null;
                $recoveringStates = isset($n->recovering_states) ? join(',',array_map(fn($f) => ED::$factionState[$f], $n->recovering_states)) : null;
                $faction = Faction::firstOrNew([
                    'name' => $n->name
                ],[
                    'name' => $n->name
                ]);
                $faction->allegiance = $n->allegiance;
                $faction->government = $n->government;
                $faction->state = ED::$factionState[$n->state];
                $faction->influence = $n->influence;
                $faction->happiness = $n->happiness != "" ? ED::$happiness[$n->happiness] : "";
                $faction->active_states = $activeStates;
                $faction->pending_states = $pendingStates;
                $faction->recovering_states = $recoveringStates;
                $faction->save();

                $factionIds[] = $faction->id;

                $history = FactionHistory::create(['system_id' => $system->id, 'faction_id' => $faction->id, 'influence' => $n->influence]);
            }

            $faction = $system->factions()->sync($factionIds);

            $factionId = Faction::select('id')->where('name', $payload->system->faction->name)->first();
            
            $system->faction_id = $factionId->id ?? 0;
            $system->save();
        }

        if(property_exists($payload, 'conflicts')) {
            foreach($payload->conflicts as $key => $n) {
                $conflict = [
                    'faction1' => $n->faction1->name,
                    'faction2' => $n->faction2->name,
                    'faction1_stake' => $n->faction1->stake != "" ? $n->faction1->stake : null,
                    'faction2_stake' => $n->faction2->stake != "" ? $n->faction2->stake : null,
                    'faction1_days_won' => $n->faction1->wondays,
                    'faction2_days_won' => $n->faction2->wondays,
                    'war_type' => $n->wartype,
                    'status' => $n->status != "" ? $n->status : null,
                ];
                $system->conflicts()->updateOrCreate(['faction1' => $n->faction1->name, 'faction2' => $n->faction2->name], $conflict);
            }
        }

        if(property_exists($payload, 'thargoid') && $payload->thargoid != null) {
            $system->thargoid()->updateOrCreate(['system_id' => $system->id], [
                'current_state' => ED::$thargoid[$payload->thargoid->current_state] ?? '',
                'days_remaining' => $payload->thargoid->days_remaining ?? 0,
                'success_state' => ED::$thargoid[$payload->thargoid->success_state] ?? '',
                'failure_state' => ED::$thargoid[$payload->thargoid->failure_state] ?? '',
                'remaining_ports' => $payload->thargoid->remaining_ports,
                'success_reached' => $payload->thargoid->success_reached,
                'war_progress' => $payload->thargoid->war_progress,
            ]);
        }

        return $system;
    }
}
