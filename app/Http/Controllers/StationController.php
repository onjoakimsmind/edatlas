<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\System;
use App\Models\Market;
use App\Models\Faction;
use App\Models\CommodityHistory;
use Illuminate\Http\Request;
use App\Providers\ED;
use App\Providers\SystemProvider;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stations = Station::paginate(25);

        return response()->json($stations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //if(!\Gate::allows('admin') && !\Gate::allows('moderator')) return response()->json(['error' => 'Unauthorized'], 401);
        $payload = json_decode(json_encode(collect($request->input('data'))), false);

        $sPayload = new \stdClass();
        $sPayload->system = $payload->system;
        if(property_exists($payload, 'factions')) $sPayload->factions = $payload->factions;
        if(property_exists($payload, 'conflicts')) $sPayload->conflicts = $payload->conflicts;
        $system = SystemProvider::store($sPayload);

        $station = Station::firstOrNew(['market_id' => $payload->station->market_id], [
            'system_id' => $system->id,
            'name' => $payload->station->name,
            'market_id' => $payload->station->market_id,
        ]);

        if(property_exists($payload->station, 'economy')) $station->economy = ED::$economy[$payload->station->economy];
        if(property_exists($payload->station, 'government')) $station->government = ED::$government[$payload->station->government];
        if(property_exists($payload->station, 'distance')) $station->distance_to_arrival = $payload->station->distance;
        if(property_exists($payload->station, 'landing_pads')) $station->landing_pads = json_encode($payload->station->landing_pads);
        if(property_exists($payload->station, 'allegiance')) $station->allegiance = $payload->station->allegiance;
        if(property_exists($payload->station, 'type')) $station->type = ED::$stationTypes[$payload->station->type];
        if(property_exists($payload->station, 'state')) $station->state = $payload->station->state ? ED::$stationStates[$payload->station->state] : '';

        if(property_exists($payload->station, 'faction')) {
            $factionId = Faction::select('id')->where('name', $payload->station->faction->name)->first()->id ?? 0;
            $station->faction_id = $factionId;
        } else {
            $station->faction_id = 0;
        }

        $station->save();
        
        if(property_exists($payload, 'economies')) {
            $station->economies()->delete();
            $station->economies()->createMany(array_map(fn($n) => [
                'name' => ED::$economy[$n->name],
                'proportion' => $n->proportion
            ], $payload->station->economies));
        }
        

        $station->services()->delete();
        $station->services()->createMany(array_map(fn($n) => [
                'name' => $n,
            ], $payload->station->services));

        $station->market()->firstOrCreate(['id' => $payload->station->market_id, 'id' => $payload->station->market_id]);
        
        $status = $station->wasRecentlyCreated ? 201 : 202;

        return response()->json($station, $status);
    }

    public function storeMarket(Request $request) {
        $payload = json_decode(json_encode(collect($request->input('data'))), false);
        
        $system = System::firstWhere('name',$payload->system->name);

        if(!$system) {
            return response()->json(['error' => 'System not found'], 404);
        }

        $station = Station::firstOrNew(['market_id' => $payload->station->market_id], [
            'system_id' => $system->id,
            'name' => $payload->station->name,
            'market_id' => $payload->station->market_id,
        ]);

        $station->save();

        $market = $station->market()->firstOrNew(['id' => $station->market_id], [
                'id' => $station->market_id,
                'station_id' => $station->id,
            ]);

        $market->save();

        $status = 201;
        $key = [];

        if(property_exists($payload, 'shipyard')) {
            if($market->ships()->exists()) {
                $market->ships()->delete();
                $status = 202;
                $key[] = 'shipyard';
            }
            foreach($payload->shipyard as $n) {
                $ship = ED::$shipyard[$n->name];
                $market->ships()->create([
                    'name' => $ship['name'],
                    'symbol' => $n->name,
                ]);
            }
        }

        if(property_exists($payload, 'modules')) {
            if($market->modules()->exists()) {
                $market->modules()->delete();
                $status = 202;
                $key[] = 'modules';
            }
            foreach($payload->modules as $n) {
                $module = ED::$modules[$n->name];
                $market->modules()->create([
                    'name' => $module['name'],
                    'symbol' => $n->name,
                    'category' => $module['category'],
                    'mount' => $module['mount'],
                    'class' => $module['class'],
                    'rating' => $module['rating'],
                    'ship' => $module['ship'],
                    'guidance' => $module['guidance'],
                ]);
            }
        }

        if(property_exists($payload, 'commodities')) {
            if($market->commodities()->exists()) {
                $market->commodities()->delete();
                $status = 202;
                $key[] = 'commodities';
            }
            foreach($payload->commodities as $n) {
                $commodity = array_key_exists($n->name, ED::$commodity) ? ED::$commodity[$n->name] : ED::$rare[$n->name];
                $market->commodities()->create([
                    'name' => $commodity['name'],
                    'symbol' => $n->name,
                    'buy_price' => $n->buy_price,
                    'sell_price' => $n->sell_price,
                    'mean_price' => $n->mean_price,
                    'stock' => $n->stock,
                    'demand' => $n->demand,
                    'stock_bracket' => $n->stock_bracket != "" ? $n->stock_bracket : 0,
                    'demand_bracket' => $n->demand_bracket != "" ? $n->demand_bracket : 0,
                ]);

                CommodityHistory::create([
                    'market_id' => $market->id,
                    'name' => $commodity['name'],
                    'symbol' => $n->name,
                    'buy_price' => $n->buy_price,
                    'sell_price' => $n->sell_price
                ]);
            }
        }

        if(property_exists($payload, 'prohibited') && $payload->prohibited) {
            $key[] = 'prohibited';
            $market->prohibited()->delete();
            foreach($payload->prohibited as $n) {
                $commodity = array_key_exists($n->name, ED::$commodity) ? ED::$commodity[$n->name] : ED::$rare[$n->name];
                $market->prohibited()->create([
                    'name' => $commodity['name'],
                    'symbol' => $n->name
                ]);
            }
        }

        return response()->json([ 'key' => $key, 'market' => $market ], $status);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $market_id)
    {
        $station = Station::where('market_id', $market_id)->with(['market' => function ($q) {
            $q->with(['ships', 'modules', 'commodities', 'rareCommodities']);
        }])->first();

        return response()->json($station);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Station $station)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Station $station)
    {
        //
    }
}
