<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Resources\SystemCollection;

use Illuminate\Support\Facades\DB;

use App\Models\System;
use App\Models\Faction;

use App\Providers\ED;
use App\Providers\SystemProvider;

use Elasticsearch\ClientBuilder;

class SystemController extends Controller
{
    protected $client;
    public function __construct()
    {
        $host = [
            'host' => env('ELASTIC_HOST'),
            'port' => env('ELASTIC_PORT'),
            'scheme' => env('ELASTIC_SCHEME'),
        ];
        
        $ca = storage_path('app');
        $this->client = ClientBuilder::create()
        ->setHosts([$host['scheme'].'://'.$host['host'].':'.$host['port']])
        ->setBasicAuthentication(env('ELASTIC_USER'), env('ELASTIC_PASSWORD'))
        ->setSSLVerification(false)
        ->build();
    }

    public function index()
    {
        $page = request()->input('page') ?? 1;
        $sort = request()->input('sort') ?? 'distance';
        $order = request()->input('order') ?? 'asc';
        //$systems = System::search('*')->orderBy($sort, $order)->paginate(25, ['*'], 'page', $page);

        /*$systems = $this->client->index('systems')->search(null, [
            'limit' => 25,
            'offset' => ($page - 1) * 25,
        ])->getRaw();*/

        return response()->json($systems);
    }

    public function search(Request $request)
    {
        //$systems = System::search()->should(new Matching('name.keyword', $request->q))->get();
        $params = [
            'index' => 'systems',
            'body' => [
                '_source' => ['name', 'address', 'x', 'y', 'z', 'distance'],
                'query' => [
                    'match_phrase_prefix' => [
                        'name' => $request->q
                    ]
                    ],
                'size' => 10
            ],
        ];
        $response = $this->client->search($params);
        $systems = collect($response['hits']['hits'])->pluck('_source')->toArray();

        return response()->json($systems);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //if(!\Gate::allows('admin') && !\Gate::allows('moderator')) return response()->json(['error' => 'Unauthorized'], 401);
        $payload = json_decode(json_encode(collect($request->input('data'))), false);
        $system = SystemProvider::store($payload);

        $status = $system->wasRecentlyCreated ? 201 : 202;

        return response()->json(System::where('id', $system->id)->first(), $status);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $system)
    {
        $systems = System::where('name', $system)->with(['faction', 'factions' => function ($q) {
            $q->orderBy('influence', 'desc');
        }, 'stations' => function($q) {
            $q->with('faction')->with('services');
        }, 'stars', 'planets'])->first();

        $systems->conflicts()->exists() ? $systems->load(['conflicts' => function($query) {
            $query->whereNot('status', '');
        }]) : null;
        $systems->thargoid()->exists() ? $systems->thargoid : null;

        return response()->json($systems);
    }

    public function station(string $system, string $station) {
        $stations = System::where('name', $system)->with(['stations' => function ($q) use ($station) {
            $q->where('name', $name)->with(['market' => function ($q) {
                $q->with(['ships', 'modules', 'commodities', 'rareCommodities']);
            }]);
        }])->first();

        return response()->json($stations);
    }

    public function stations(string $system) {
        $station = System::where('name', $system)->with(['stations'])->first();

        return response()->json($station);
    }

    public function factions(string $system) {
        $factions = System::where('name', $system)->with(['factions' => function ($q) {
            $q->with('history');
        }])->first();

        return response()->json($factions);
    }

    public function faction(string $system, string $faction) {
        $factions = System::where('name', $system)->with(['factions' => function ($q) use ($faction) {
            $q->where('name', $faction)->orderBy('updated_at', 'desc');
        }])->first();

        return response()->json($factions);
    }

    public function conflicts(string $system) {
        $conflicts = System::where('name', $system)->with(['conflicts'])->first();

        return response()->json($conflicts);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, System $system)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(System $system)
    {
        //
    }
}
