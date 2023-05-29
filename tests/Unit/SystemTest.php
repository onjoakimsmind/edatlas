<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Models\System;
use App\Models\Faction;
use App\Models\Station;
use App\Models\ThargoidWar;
use App\Models\Star;
use App\Models\Planet;

class SystemTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic unit test example.
     */
    public function test_system_can_be_created(): void
    {
        $params = [
            'name' => 'Sol',
            'address' => 12345,
            'x' => 0,
            'y' => 0,
            'z' => 0,
            'population' => 100000000000,
            'security' => 'high',
            'allegiance' => 'Federation',
            'government' => 'Democracy',
            'economy' => 'Industrial',
            'second_economy' => 'Refinery',
            'distance' => 0,
        ];

        $system = System::factory()->create($params);

        $this->assertModelExists($system);
        $this->assertDatabaseCount(System::class, 1);
        $this->assertEquals($system->name, $params['name']);
        $this->assertEquals($system->address, $params['address']);
        $this->assertEquals($system->x, $params['x']);
        $this->assertEquals($system->y, $params['y']);
        $this->assertEquals($system->z, $params['z']);
        $this->assertEquals($system->population, $params['population']);
        $this->assertEquals($system->security, $params['security']);
        $this->assertEquals($system->allegiance, $params['allegiance']);
        $this->assertEquals($system->government, $params['government']);
        $this->assertEquals($system->economy, $params['economy']);
        $this->assertEquals($system->second_economy, $params['second_economy']);
        $this->assertEquals($system->distance, $params['distance']);
    }

    public function test_system_can_be_deleted(): void
    {
        $system = System::factory()->create();

        $this->assertModelExists($system);
        $this->assertDatabaseCount(System::class, 1);

        $system->delete();

        $this->assertModelMissing($system);
        $this->assertDatabaseCount(System::class, 0);
    }

    public function test_system_can_be_updated(): void
    {
        $system = System::factory()->create();

        $this->assertModelExists($system);
        $this->assertDatabaseCount(System::class, 1);

        $system->name = 'Sol';
        $system->save();
        
        $this->assertModelExists($system);
        $this->assertDatabaseCount(System::class, 1);
        $this->assertEquals($system->name, 'Sol');
    }

    public function test_system_can_be_found(): void
    {
        $system = System::factory()->create();

        $this->assertModelExists($system);
        $this->assertDatabaseCount(System::class, 1);

        $found_system = System::find($system->id);

        $this->assertModelExists($found_system);
        $this->assertDatabaseCount(System::class, 1);
        $this->assertEquals($system->name, $found_system->name);
    }

    public function test_system_can_have_factions() {
        $system = System::factory()->create();
        $faction = Faction::factory()->make();

        $system->factions()->create([
            'name' => $faction->name,
            'state' => $faction->state,
            'allegiance' => $faction->allegiance,
            'government' => $faction->government,
            'influence' => $faction->influence,
            'happiness' => $faction->happiness,
            'active_states' => json_encode($faction->active_states),
            'pending_states' => json_encode($faction->pending_states),
            'recovering_states' => json_encode($faction->recovering_states),
        ]);

        $found_faction = Faction::where('system_id', $system->id)->first();
        $this->assertModelExists($system);
        $this->assertDatabaseCount(System::class, 1);
        $this->assertDatabaseCount(Faction::class, 1);
        $this->assertEquals($system->id, $found_faction->system_id);
    }

    public function test_system_can_have_stations() {
        $system = System::factory()->create();
        $station = Station::factory()->make();

        $system->stations()->create([
            'name' => $station->name,
            'market_id' => $station->market_id,
            'type' => $station->type,
            'distance_to_arrival' => $station->distance_to_arrival,
            'allegiance' => $station->allegiance,
            'government' => $station->government,
            'economy' => $station->economy,
            'state' => $station->state,
            'landing_pads' => json_encode($station->landing_pads),
            'faction_id' => $station->faction_id,
        ]);

        $found_station = Station::where('system_id', $system->id)->first();

        $this->assertModelExists($system);
        $this->assertDatabaseCount(System::class, 1);
        $this->assertDatabaseCount(Station::class, 1);
        $this->assertEquals($system->id, $found_station->system_id);
    }

    public function test_system_can_have_thargoid_war() {
        $system = System::factory()->create();
        $thargoid = ThargoidWar::factory()->make();

        $system->thargoid()->create([
            'current_state' => $thargoid->current_state,
            'days_remaining' => $thargoid->days_remaining,
            'success_state' => $thargoid->success_state,
            'failure_state' => $thargoid->failure_state,
            'remaining_ports' => $thargoid->remaining_ports,
            'success_reached' => $thargoid->success_reached,
            'war_progress' => $thargoid->war_progress,
        ]);

        $found_thargoid = ThargoidWar::where('system_id', $system->id)->first();

        $this->assertModelExists($system);
        $this->assertDatabaseCount(System::class, 1);
        $this->assertDatabaseCount(ThargoidWar::class, 1);
        $this->assertEquals($system->id, $found_thargoid->system_id);
    }

    public function test_system_can_have_stars() {
        $system = System::factory()->create();
        $star = Star::factory()->make();
        
        $system->stars()->create([
            'body_id' => $star->body_id,
            'name' => $star->name,
            'class' => $star->class,
            'absolute_magnitude' => $star->absolute_magnitude,
            'age' => $star->age,
            'ascending_node' => $star->ascending_node,
            'distance_to_arrival' => $star->distance_to_arrival,
            'eccentricity' => $star->eccentricity,
            'is_main_star' => $star->is_main_star,
            'is_scoopable' => $star->is_scoopable,
            'luminosity' => $star->luminosity,
            'mean_anomaly' => $star->mean_anomaly,
            'orbital_inclination' => $star->orbital_inclination,
            'orbital_period' => $star->orbital_period,
            'radius' => $star->radius,
            'rotation_period' => $star->rotation_period,
            'semi_major_axis' => $star->semi_major_axis,
            'stellar_mass' => $star->stellar_mass,
            'surface_temperature' => $star->surface_temperature,
            'periapsis' => $star->periapsis,
            'type' => $star->type,
            'axial_tilt' => $star->axial_tilt,
            'parents' => json_encode($star->parents),
        ]);

        $found_star = Star::where('system_id', $system->id)->first();

        $this->assertModelExists($system);
        $this->assertDatabaseCount(System::class, 1);
        $this->assertDatabaseCount(Star::class, 1);
        $this->assertEquals($system->id, $found_star->system_id);
    }

    public function test_system_can_have_planets() {
        $system = System::factory()->create();
        $planet = Planet::factory()->make();

        $system->planets()->create([
            'body_id' => $planet->body_id,
            'name' => $planet->name,
            'atmosphere_type' => $planet->atmosphere_type,
            'atmosphere_composition' => json_encode($planet->atmosphere_composition),
            'ascending_node' => $planet->ascending_node,
            'axial_tilt' => $planet->axial_tilt,
            'composition' => json_encode($planet->composition),
            'class' => $planet->class,
            'distance_to_arrival' => $planet->distance_to_arrival,
            'eccentricity' => $planet->eccentricity,
            'gravity' => $planet->gravity,
            'is_landable' => $planet->is_landable,
            'mass' => $planet->mass,
            'mean_anomaly' => $planet->mean_anomaly,
            'orbital_inclination' => $planet->orbital_inclination,
            'orbital_period' => $planet->orbital_period,
            'radius' => $planet->radius,
            'rotation_period' => $planet->rotation_period,
            'semi_major_axis' => $planet->semi_major_axis,
            'surface_pressure' => $planet->surface_pressure,
            'surface_temperature' => $planet->surface_temperature,
            'tidally_locked' => $planet->tidally_locked,
            'terraforming_state' => $planet->terraforming_state,
            'volcanism' => $planet->volcanism,
            'parents' => json_encode($planet->parents),
        ]);

        $found_planet = Planet::where('system_id', $system->id)->first();

        $this->assertModelExists($system);
        $this->assertDatabaseCount(System::class, 1);
        $this->assertDatabaseCount(Planet::class, 1);
        $this->assertEquals($system->id, $found_planet->system_id);
    }
}
