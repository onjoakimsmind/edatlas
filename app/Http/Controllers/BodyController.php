<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\System;
use App\Models\Star;
use App\Models\Planet;
use App\Models\Ring;

use App\Providers\SystemProvider;
use App\Providers\ED;

class BodyController extends Controller
{
    public function show(string $name) {
        $system = System::where('name', $name)->with(['stars' => fn($q) => $q->with(['rings']), 'planets' => fn($q) => $q->with(['rings'])])->first();

        return response()->json($system);
    }

    public function store(Request $request) {

        $payload = json_decode(json_encode(collect($request->input('data'))), false);

        $sPayload = new \stdClass();
        $sPayload->system = $payload->system;
        $system = SystemProvider::store($sPayload);

        if(property_exists($payload, 'star')) {
            $star = Star::firstOrNew(['system_id' => $system->id, 'name' => $payload->star->name],
            [
                'body_id' => $payload->star->body_id,
                'name' => $payload->star->name,
            ]);

            if(property_exists($payload->star, 'ascending_node')) $star->ascending_node = $payload->star->ascending_node;
            if(property_exists($payload->star, 'eccentricity')) $star->eccentricity = $payload->star->eccentricity;
            if(property_exists($payload->star, 'mean_anomaly')) $star->mean_anomaly = $payload->star->mean_anomaly;
            if(property_exists($payload->star, 'orbital_inclination')) $star->orbital_inclination = $payload->star->orbital_inclination;
            if(property_exists($payload->star, 'orbital_period')) $star->orbital_period = $payload->star->orbital_period;
            if(property_exists($payload->star, 'semi_major_axis')) $star->semi_major_axis = $payload->star->semi_major_axis;
            if(property_exists($payload->star, 'class')) $star->class = $payload->star->class;
            if(property_exists($payload->star, 'absolute_magnitude')) $star->absolute_magnitude = $payload->star->absolute_magnitude;
            if(property_exists($payload->star, 'age')) $star->age = $payload->star->age;
            if(property_exists($payload->star, 'distance_to_arrival')) $star->distance_to_arrival = $payload->star->distance;
            if(property_exists($payload->star, 'luminosity')) $star->luminosity = $payload->star->luminosity;
            if(property_exists($payload->star, 'radius')) $star->radius = $payload->star->radius;
            if(property_exists($payload->star, 'rotation_period')) $star->rotation_period = $payload->star->rotation_period;
            if(property_exists($payload->star, 'stellar_mass')) $star->stellar_mass = $payload->star->stellar_mass;
            if(property_exists($payload->star, 'surface_temperature')) $star->surface_temperature = $payload->star->surface_temperature;
            if(property_exists($payload->star, 'star_type')) $star->type = $payload->star->star_type;
            if(property_exists($payload->star, 'parents')) $star->parents = json_encode($payload->star->parents);

            $star->is_main_star = ED::isMainStar($payload->star->distance);
            $star->is_scoopable = ED::isScoopable($payload->star->star_type);

            $star->save();

            if(property_exists($payload->star, 'rings') && is_array($payload->star->rings)) {
                foreach($payload->star->rings as $key => $value)
                {
                    $n = $value;
                    Ring::updateOrCreate(['system_id' => $system->id, 'star_id' => $star->id, 'name' => $n->name] ,[
                        'system_id' => $system->id,
                        'planet_id' => null,
                        'star_id' => $star->id,
                        'name' => $n->name,
                        'type' => ED::$rings[$n->type],
                        'symbol' => $n->type,
                        'mass' => $n->mass,
                        'inner_radius' => $n->innerRadius,
                        'outer_radius' => $n->outerRadius,
                    ]);
                }
            }
            $status = $star->wasRecentlyCreated ? 201 : 202;
            return response()->json($star, $status);
        }

        if(property_exists($payload, 'planet') && is_array($payload->planet->rings)) {
            $planet = Planet::firstOrNew(['system_id' => $system->id], [
                'system_id' => $system->id,
                'body_id' => $payload->planet->body_id,
                'name' => $payload->planet->name,
            ]);

            if(property_exists($payload->planet, 'atmosphere_type')) $planet->atmosphere_type = $payload->planet->atmosphere;
            if(property_exists($payload->planet, 'atmosphere_composition')) $planet->atmosphere_composition = json_encode($payload->planet->atmosphere_composition);
            if(property_exists($payload->planet, 'ascending_node')) $planet->ascending_node = $payload->planet->ascending_node;
            if(property_exists($payload->planet, 'axial_tilt')) $planet->axial_tilt = $payload->planet->axial_tilt;
            if(property_exists($payload->planet, 'composition')) $planet->composition = json_encode($payload->planet->composition);
            if(property_exists($payload->planet, 'planet_class')) $planet->class = $payload->planet->planet_class;
            if(property_exists($payload->planet, 'distance_to_arrival')) $planet->distance_to_arrival = $payload->planet->distance;
            if(property_exists($payload->planet, 'eccentricity')) $planet->eccentricity = $payload->planet->eccentricity;
            if(property_exists($payload->planet, 'gravity')) $planet->gravity = $payload->planet->surface_gravity;
            if(property_exists($payload->planet, 'is_landable')) $planet->is_landable = $payload->planet->landable;
            if(property_exists($payload->planet, 'mass')) $planet->mass = $payload->planet->mass;
            if(property_exists($payload->planet, 'mean_anomaly')) $planet->mean_anomaly = $payload->planet->mean_anomaly;
            if(property_exists($payload->planet, 'orbital_inclination')) $planet->orbital_inclination = $payload->planet->orbital_inclination;
            if(property_exists($payload->planet, 'orbital_period')) $planet->orbital_period = $payload->planet->orbital_period;
            if(property_exists($payload->planet, 'radius')) $planet->radius = $payload->planet->radius;
            if(property_exists($payload->planet, 'rotation_period')) $planet->rotation_period = $payload->planet->rotation_period;
            if(property_exists($payload->planet, 'semi_major_axis')) $planet->semi_major_axis = $payload->planet->semi_major_axis;
            if(property_exists($payload->planet, 'surface_pressure')) $planet->surface_pressure = $payload->planet->surface_pressure;
            if(property_exists($payload->planet, 'surface_temperature')) $planet->surface_temperature = $payload->planet->surface_temperature;
            if(property_exists($payload->planet, 'tidally_locked')) $planet->tidally_locked = $payload->planet->tidal_lock;
            if(property_exists($payload->planet, 'terraforming_state')) $planet->terraforming_state = $payload->planet->terraforming_state ?? "";
            if(property_exists($payload->planet, 'volcanism')) $planet->volcanism = $payload->planet->volcanism ?? "";
            if(property_exists($payload->planet, 'parents')) $planet->parents = json_encode($payload->planet->parents);
            
            $planet->save();

            if(property_exists($payload->planet, 'rings')) {
                foreach($payload->planet->rings as $key => $value)
                {
                    $n = $value;
                    Ring::updateOrCreate(['system_id' => $system->id, 'planet_id' => $planet->id, 'name' => $n->name] ,[
                        'system_id' => $system->id,
                        'planet_id' => $planet->id,
                        'star_id' => null,
                        'name' => $n->name,
                        'type' => ED::$rings[$n->type],
                        'symbol' => $n->type,
                        'mass' => $n->mass,
                        'inner_radius' => $n->innerRadius,
                        'outer_radius' => $n->outerRadius,
                    ]);
                }
            }
            $status = $planet->wasRecentlyCreated ? 201 : 202;
            return response()->json($planet, $status);
        }
    }
}
