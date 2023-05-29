<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Providers\ED;

use App\Models\System;
use App\Models\Faction;
use App\Models\ThargoidWar;
use App\Models\Conflict;

class SystemTest extends TestCase
{
    use DatabaseMigrations;

    public function test_get_all(): void
    {
        
        $this->seed();

        $response = $this->get('/api/v1/systems');

        $content = json_decode($response->getContent());
        $response->assertStatus(200);
        $this->assertTrue(count($content->data) == 25);
    }

    public function test_get_one(): void
    {
        
        $systems = System::factory()->create();

        $system = (object)collect($systems->first())->all();
        $response = $this->get("/api/v1/systems/{$system->name}");

        $content = json_decode($response->getContent());
        
        $response->assertStatus(200);
        $this->assertEquals($system->name, $content->name);
        $this->assertEquals($system->address, $content->address);
        $this->assertEquals($system->x, $content->x);
        $this->assertEquals($system->y, $content->y);
        $this->assertEquals($system->z, $content->z);
        $this->assertEquals($system->population, $content->population);
        $this->assertEquals($system->security, $content->security);
        $this->assertEquals($system->allegiance, $content->allegiance);
        $this->assertEquals($system->government, $content->government);
        $this->assertEquals($system->economy, $content->economy);
        $this->assertEquals($system->second_economy, $content->second_economy);
        $this->assertEquals(round($system->distance, 2), round($content->distance, 2));
    }

    public function test_post()
    {
        $system = System::factory()->make();

        $payload = [
            'data' => [
                'system' => [
                    'name' => $system->name,
                    'address' => $system->address,
                    'location' => [
                        'x' => $system->x,
                        'y' => $system->y,
                        'z' => $system->z
                    ],
                    'population' => $system->population,
                    'security' => array_search($system->security, ED::$security),
                    'allegiance' => $system->allegiance,
                    'government' => array_search($system->government, ED::$government),
                    'economy' => array_search($system->economy, ED::$economy),
                    'second_economy' => array_search($system->second_economy, ED::$economy),
                    'powers' => $system->powers,
                    'pps' => $system->pps,
                ]
            ]
        ];
        $response = $this->postJson('/api/v1/systems', $payload, ['Content-Type' => 'application/json']);
        $content = json_decode($response->getContent());
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('systems', ['name' => $system->name]);
        $this->assertTrue(true);
    }

    public function test_post_with_factions()
    {
        $system = System::factory()->make();
        $factions = Faction::factory()->count(2)->make();
 
        $payload = [
            'data' => [
                'system' => [
                    'name' => $system->name,
                    'address' => $system->address,
                    'location' => [
                        'x' => $system->x,
                        'y' => $system->y,
                        'z' => $system->z
                    ],
                    'population' => $system->population,
                    'security' => array_search($system->security, ED::$security),
                    'allegiance' => $system->allegiance,
                    'government' => array_search($system->government, ED::$government),
                    'economy' => array_search($system->economy, ED::$economy),
                    'second_economy' => array_search($system->second_economy, ED::$economy),
                    'powers' => json_decode($system->powers),
                    'pps' => $system->pps,
                    'faction' => [
                        'name' => $factions[0]->name,
                        'state' => null,
                    ]
                ],
                'factions' => collect($factions)->map(function($faction) {
                    return [
                        'name' => $faction->name,
                        'state' => array_search($faction->state, ED::$factionState),
                        'allegiance' => $faction->allegiance,
                        'government' => array_search($faction->government, ED::$government),
                        'influence' => $faction->influence,
                        'happiness' => array_search($faction->happiness, ED::$happiness),
                        'active_states' => $faction->active_states ? array_map(function($state) {
                            return array_search($state, ED::$factionState);
                        }, $faction->active_states) : null,
                        'pending_states' => $faction->pending_states ? array_map(function($state) {
                            return array_search($state, ED::$factionState);
                        }, $faction->pending_states) : null,
                        'recovering_states' => $faction->recovering_states ? array_map(function($state) {
                            return array_search($state, ED::$factionState);
                        }, $faction->recovering_states) : null,
                    ];
                })
            ]
        ];
        $response = $this->postJson('/api/v1/systems', $payload, ['Content-Type' => 'application/json']);
        $response->assertStatus(201);
        $collection = json_decode(collect($response->getContent())->first());
        $this->assertDatabaseHas('systems', ['name' => $system->name]);
        $this->assertDatabaseHas('factions', ['name' => $factions[0]->name, 'system_id' => $collection->id]);
        $this->assertDatabaseHas('systems', ['faction_id' => $collection->faction_id]);
    }

    public function test_post_with_thargoid_war()
    {
        $system = System::factory()->make();
        $thargoid = ThargoidWar::factory()->make();
 
        $payload = [
            'data' => [
                'system' => [
                    'name' => $system->name,
                    'address' => $system->address,
                    'location' => [
                        'x' => $system->x,
                        'y' => $system->y,
                        'z' => $system->z
                    ],
                    'population' => $system->population,
                    'security' => array_search($system->security, ED::$security),
                    'allegiance' => $system->allegiance,
                    'government' => array_search($system->government, ED::$government),
                    'economy' => array_search($system->economy, ED::$economy),
                    'second_economy' => array_search($system->second_economy, ED::$economy),
                    'powers' => json_decode($system->powers),
                    'pps' => $system->pps,
                ],
                'thargoid' => [
                    'current_state' => $thargoid->current_state ? array_search($thargoid->current_state, ED::$thargoid) : '',
                    'days_remaining' => $thargoid->days_remaining,
                    'success_state' => $thargoid->success_state ? array_search($thargoid->success_state, ED::$thargoid) : '',
                    'failure_state' => $thargoid->failure_state ? array_search($thargoid->failure_state, ED::$thargoid) : '',
                    'remaining_ports' => $thargoid->remaining_ports,
                    'success_reached' => $thargoid->success_reached,
                    'war_progress' => $thargoid->war_progress,
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/systems', $payload, ['Content-Type' => 'application/json']);
        $response->assertStatus(201);

        $collection = json_decode(collect($response->getContent())->first());
        $this->assertDatabaseHas('systems', ['name' => $system->name]);
        $this->assertDatabaseHas('thargoid_wars', ['system_id' => $collection->id]);
    }

    public function test_post_with_conflict()
    {
        $system = System::factory()->make();
        $conflicts = Conflict::factory()->count(4)->make();

        $payload = [
            'data' => [
                'system' => [
                    'name' => $system->name,
                    'address' => $system->address,
                    'location' => [
                        'x' => $system->x,
                        'y' => $system->y,
                        'z' => $system->z
                    ],
                    'population' => $system->population,
                    'security' => array_search($system->security, ED::$security),
                    'allegiance' => $system->allegiance,
                    'government' => array_search($system->government, ED::$government),
                    'economy' => array_search($system->economy, ED::$economy),
                    'second_economy' => array_search($system->second_economy, ED::$economy),
                    'powers' => json_decode($system->powers),
                    'pps' => $system->pps,
                ],
                'conflicts' => collect($conflicts)->map(function($conflict) {
                    return [
                        'faction1' => [
                            'name' => $conflict->faction1,
                            'stake' => $conflict->faction1_stake,
                            'wondays' => $conflict->faction1_days_won,
                        ],
                        'faction2' => [
                            'name' => $conflict->faction2,
                            'stake' => $conflict->faction2_stake,
                            'wondays' => $conflict->faction2_days_won,
                        ],
                        'wartype' => $conflict->war_type,
                        'status' => $conflict->status,
                    ];
                })
            ]
        ];

        $response = $this->postJson('/api/v1/systems', $payload, ['Content-Type' => 'application/json']);
        $response->assertStatus(201);

        $collection = json_decode(collect($response->getContent())->first());
        $this->assertDatabaseHas('systems', ['name' => $system->name]);
        $this->assertDatabaseHas('conflicts', ['system_id' => $collection->id]);
    }

    public function test_post_with_all()
    {
        $system = System::factory()->make();
        $conflicts = Conflict::factory()->count(4)->make();
        $factions = Faction::factory()->count(2)->make();
        $thargoid = ThargoidWar::factory()->make();
 
        $payload = [
            'data' => [
                'system' => [
                    'name' => $system->name,
                    'address' => $system->address,
                    'location' => [
                        'x' => $system->x,
                        'y' => $system->y,
                        'z' => $system->z
                    ],
                    'population' => $system->population,
                    'security' => array_search($system->security, ED::$security),
                    'allegiance' => $system->allegiance,
                    'government' => array_search($system->government, ED::$government),
                    'economy' => array_search($system->economy, ED::$economy),
                    'second_economy' => array_search($system->second_economy, ED::$economy),
                    'powers' => json_decode($system->powers),
                    'pps' => $system->pps,
                    'faction' => [
                        'name' => $factions[0]->name,
                        'state' => null
                    ]
                ],
                'conflicts' => collect($conflicts)->map(function($conflict) {
                    return [
                        'faction1' => [
                            'name' => $conflict->faction1,
                            'stake' => $conflict->faction1_stake,
                            'wondays' => $conflict->faction1_days_won,
                        ],
                        'faction2' => [
                            'name' => $conflict->faction2,
                            'stake' => $conflict->faction2_stake,
                            'wondays' => $conflict->faction2_days_won,
                        ],
                        'wartype' => $conflict->war_type,
                        'status' => $conflict->status,
                    ];
                }),
                'factions' => collect($factions)->map(function($faction) {
                    return [
                        'name' => $faction->name,
                        'state' => array_search($faction->state, ED::$factionState),
                        'allegiance' => $faction->allegiance,
                        'government' => array_search($faction->government, ED::$government),
                        'influence' => $faction->influence,
                        'happiness' => array_search($faction->happiness, ED::$happiness),
                        'active_states' => $faction->active_states ? array_map(function($state) {
                            return array_search($state, ED::$factionState);
                        }, $faction->active_states) : null,
                        'pending_states' => $faction->pending_states ? array_map(function($state) {
                            return array_search($state, ED::$factionState);
                        }, $faction->pending_states) : null,
                        'recovering_states' => $faction->recovering_states ? array_map(function($state) {
                            return array_search($state, ED::$factionState);
                        }, $faction->recovering_states) : null,
                    ];
                }),
                'thargoid' => [
                    'current_state' => $thargoid->current_state ? array_search($thargoid->current_state, ED::$thargoid) : '',
                    'days_remaining' => $thargoid->days_remaining,
                    'success_state' => $thargoid->success_state ? array_search($thargoid->success_state, ED::$thargoid) : '',
                    'failure_state' => $thargoid->failure_state ? array_search($thargoid->failure_state, ED::$thargoid) : '',
                    'remaining_ports' => $thargoid->remaining_ports,
                    'success_reached' => $thargoid->success_reached,
                    'war_progress' => $thargoid->war_progress,
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/systems', $payload, ['Content-Type' => 'application/json']);
        $response->assertStatus(201);

        $collection = json_decode(collect($response->getContent())->first());
        $this->assertDatabaseHas('systems', ['name' => $system->name]);
        $this->assertDatabaseHas('conflicts', ['system_id' => $collection->id]);
        $this->assertDatabaseHas('thargoid_wars', ['system_id' => $collection->id]);
        $this->assertDatabaseHas('factions', ['system_id' => $collection->id]);
    }
}
