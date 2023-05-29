<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Logger;
use App\Models\Station;
use App\Models\System;
use App\Models\Market;
use App\Models\Faction;
use App\Providers\ED;
use App\Providers\SystemProvider;

use Elasticsearch\ClientBuilder;

class EDDN extends Controller
{
    public function receiver(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $raw = $request->getContent();
        list($domain, $unused, $schema, $version) = explode("/",substr($data['$schemaRef'], 8));        
        switch($schema) {
            case 'journal':
                $this->journal($data, $raw);
                break;
            case 'approachsettlement':
                break;
            case 'outfitting':
                break;
            case 'shipyard':
                break;
            case 'commodity':
                break;
            case 'scanbarycentre':
        }
    }

    public function journal($data, $msg): void {
        switch($data['message']['event']) {
            case 'Docked' :
                $this->docked($data['message'], $msg);
                break;
            case 'FSDJump' :
                $this->fsdjump($data['message'], $msg);
                break;
            case 'Scan' :
                $this->scan($data['message'], $msg);
                break;
            case 'Location' :
                $this->location($data['message'], $msg);
                break;
            
        }
    }

    public function docked($data, $msg)
    {
        dd($data);
    }

    public function fsdjump($data, $msg)
    {
        dd($data);
    }

    public function scan($data, $msg)
    {
        dd($data);
    }

    public function location($data, $msg)
    {
        $system = [
            'name' => $data['StarSystem'],
            'x' => $data['StarPos'][0],
            'y' => $data['StarPos'][1],
            'z' => $data['StarPos'][2],
            'address' => $data['SystemAddress'] ?? 0,
            'population' => $data['Population'] ?? 0,
            'security' => $data['SystemSecurity'] ?? '',
            'allegiance' => $data['SystemAllegiance'] ?? '',
            'faction' => $data['SystemFaction']['name'] ?? '',

        ];
        dd($data);
    }

    public function systems($data, $msg): System
    {
        $system = System::firstOrNew([
            'name' => $data['StarSystem'],
            'address' => $data['SystemAddress'],
        ], [
            'name' => $data['StarSystem'],
            'x' => $data['StarPos'][0],
            'y' => $data['StarPos'][1],
            'z' => $data['StarPos'][2],
            'address' => $data['SystemAddress'] ?? 0,
        ]);
        $system->population = $data['Population'] ?? 0;
        $system->security = ED::$security[$data['SystemSecurity']] ?? '';
        $system->allegiance = $data['SystemAllegiance'] ?? '';
        
        $system->save();

        return $system;
    }

    public function factions($data, $system): Faction
    {
        $faction = Faction::firstOrNew([
            'name' => $data['Faction'],
        ], [
            'name' => $data['Faction'],
        ]);
        $faction->state = $data['FactionState'] ?? '';
        $faction->allegiance = $data['FactionAllegiance'] ?? '';
        $faction->government = $data['FactionGovernment'] ?? '';
        $faction->influence = $data['Influence'] ?? 0;
        $faction->happiness = $data['Happiness'] ?? '';
        $faction->active_states = $data['ActiveStates'] ?? null;
        $faction->pending_states = $data['PendingStates'] ?? null;
        $faction->recovering_states = $data['RecoveringStates'] ?? null;
        $faction->save();

        return $faction;
    }

    public function logger($msg): void
    {

    }
}
