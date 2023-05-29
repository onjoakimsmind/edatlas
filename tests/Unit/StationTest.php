<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Models\System;
use App\Models\Station;
use App\Models\Faction;

class StationTest extends TestCase
{

    use DatabaseMigrations;
    
    public function test_station_can_be_created(): void
    {
        $system = System::factory()->create();
        $params = [
            'system_id' => $system->id,
            'market_id' => 1,
            'name' => 'Nile Gatewat',
            'type' => 'Coriolis Starport',
            'distance_to_arrival' => 15,
            'allegiance' => 'Federation',
            'government' => 'Democracy',
            'economy' => 'Industrial',
            'state' => 'Boom',
            'landing_pads' =>  json_encode([
                'Small' => 0,
                'Medium' => 0,
                'Large' => 0,
            ]),
            'faction_id' => 1,
        ];

        $station = Station::factory()->create($params);

        $this->assertModelExists($station);
        $this->assertDatabaseCount(Station::class, 1);
        $this->assertEquals($station->system_id, $params['system_id']);
        $this->assertEquals($station->market_id, $params['market_id']);
        $this->assertEquals($station->name, $params['name']);
        $this->assertEquals($station->type, $params['type']);
        $this->assertEquals($station->distance_to_arrival, $params['distance_to_arrival']);
        $this->assertEquals($station->allegiance, $params['allegiance']);
        $this->assertEquals($station->government, $params['government']);
        $this->assertEquals($station->economy, $params['economy']);
        $this->assertEquals($station->state, $params['state']);
        $this->assertEquals($station->landing_pads, $params['landing_pads']);
        $this->assertEquals($station->faction_id, $params['faction_id']);
    }

    public function test_station_can_be_deleted(): void {
        $system = System::factory()->create();
        $params = ['system_id' => $system->id];

        $station = Station::factory()->create($params);

        $this->assertModelExists($station);
        $this->assertDatabaseCount(Station::class, 1);

        $station->delete();

        $this->assertModelMissing($station);
        $this->assertDatabaseCount(Station::class, 0);
    }

    public function test_station_can_be_updated(): void {
        $system = System::factory()->create();
        $params = ['system_id' => $system->id];

        $station = Station::factory()->create($params);

        $this->assertModelExists($station);
        $this->assertDatabaseCount(Station::class, 1);

        $station->name = 'Midway Gateway';
        $station->save();

        $this->assertModelExists($station);
        $this->assertDatabaseCount(Station::class, 1);
        $this->assertEquals($station->name, 'Midway Gateway');
    }

    public function test_station_can_be_found(): void
    {
        $system = System::factory()->create();
        $params = ['system_id' => $system->id];

        $station = Station::factory()->create($params);

        $this->assertModelExists($system);
        $this->assertDatabaseCount(System::class, 1);

        $found_station = Station::find($station->id);

        $this->assertModelExists($found_station);
        $this->assertDatabaseCount(Station::class, 1);
        $this->assertEquals($station->name, $found_station->name);
    }

    public function test_station_belongs_to_system(): void {
        $system = System::factory()->create();
        $params = [
            'system_id' => $system->id,
        ];

        $station = Station::factory()->create($params);

        $this->assertModelExists($station);
        $this->assertDatabaseCount(Station::class, 1);
        $this->assertEquals($station->system_id, $system->id);
    }

    public function test_station_can_have_faction(): void {
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
