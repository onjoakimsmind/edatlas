<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

use App\Models\System;
use App\Models\Station;
use App\Models\Faction;

class FactionTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_faction_can_be_created(): void
    {
        $system = System::factory()->create();
        $params = [
            'system_id' => $system->id,
            'name' => 'The Pilots Federation',
            'allegiance' => 'Independent',
            'government' => 'None',
            'influence' => 0.0,
            'state' => 'None',
            'active_states' => json_encode(['Boom']),
            'pending_states' => json_encode(['Boom']),
            'recovering_states' => json_encode(['Boom']),
        ];

        $faction = Faction::factory()->create($params);

        $this->assertModelExists($faction);
        $this->assertDatabaseCount(Faction::class, 1);
        $this->assertEquals($faction->system_id, $params['system_id']);
        $this->assertEquals($faction->name, $params['name']);
        $this->assertEquals($faction->allegiance, $params['allegiance']);
        $this->assertEquals($faction->government, $params['government']);
        $this->assertEquals($faction->influence, $params['influence']);
        $this->assertEquals($faction->state, $params['state']);
        $this->assertEquals($faction->active_states, $params['active_states']);
        $this->assertEquals($faction->pending_states, $params['pending_states']);
        $this->assertEquals($faction->recovering_states, $params['recovering_states']);
    }

    public function test_faction_can_be_deleted(): void {
        $system = System::factory()->create();
        $params = [
            'system_id' => $system->id,
            'name' => 'The Pilots Federation',
            'allegiance' => 'Independent',
            'government' => 'None',
            'influence' => 0.0,
            'state' => 'None',
            'active_states' => json_encode(['Boom']),
            'pending_states' => json_encode(['Boom']),
            'recovering_states' => json_encode(['Boom']),
        ];

        $faction = Faction::factory()->create($params);

        $this->assertModelExists($faction);
        $this->assertDatabaseCount(Faction::class, 1);

        $faction->delete();

        $this->assertModelMissing($faction);
        $this->assertDatabaseCount(Faction::class, 0);
    }

    public function test_faction_can_be_updated(): void {
        $system = System::factory()->create();
        $params = [
            'system_id' => $system->id,
            'name' => 'The Pilots Federation',
            'allegiance' => 'Independent',
            'government' => 'None',
            'influence' => 0.0,
            'state' => 'None',
            'active_states' => json_encode(['Boom']),
            'pending_states' => json_encode(['Boom']),
            'recovering_states' => json_encode(['Boom']),
        ];

        $faction = Faction::factory()->create($params);

        $this->assertModelExists($faction);
        $this->assertDatabaseCount(Faction::class, 1);

        $faction->influence = 99.4;
        $faction->active_states = json_encode(['Boom', 'Bust']);
        $faction->pending_states = null;
        $faction->recovering_states = null;
        $faction->save();

        $this->assertModelExists($faction);
        $this->assertDatabaseCount(Faction::class, 1);
        $this->assertEquals($faction->influence, 99.4);
        $this->assertEquals($faction->active_states, json_encode(['Boom', 'Bust']));
        $this->assertEquals($faction->pending_states, null);
        $this->assertEquals($faction->recovering_states, null);
    }

    public function test_faction_belongs_to_system(): void {
        $f = Faction::factory()->make();
        $system = System::factory()->create();
        $params = [
            'system_id' => $system->id,
            'name' => $f->name,
            'allegiance' => $f->allegiance,
            'government' => $f->government,
            'influence' => $f->influence,
            'state' => $f->state,
            'happiness' => $f->happiness,
        ];

        $faction = Faction::factory()->create($params);

        $this->assertModelExists($faction);
        $this->assertDatabaseCount(Faction::class, 1);
        $this->assertEquals($faction->system_id, $system->id);
    }

    public function test_faction_belongs_to_station(): void {
        $f = Faction::factory()->make();
        $system = System::factory()->create();
        $faction = $system->factions()->create([
            'name' => $f->name,
            'allegiance' => $f->allegiance,
            'government' => $f->government,
            'influence' => $f->influence,
            'state' => $f->state,
            'happiness' => $f->happiness,
        ]);
        $params = [
            'system_id' => $system->id,
            'faction_id' => $faction->id,
        ];

        $station = Station::factory()->create($params);

        $this->assertModelExists($station);
        $this->assertDatabaseCount(Station::class, 1);
        $this->assertEquals($station->faction_id, $faction->id);
    }
}
