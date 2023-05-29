<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Resources\SystemCollection;

use App\Models\System;
use App\Models\Faction;

use App\Providers\ED;
use App\Providers\SystemProvider;

use JeroenG\Explorer\Domain\Syntax\Matching;
use JeroenG\Explorer\Domain\Syntax\Nested;

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
                '_source' => ['*'],
                'script_fields' => [
                    'distanceBetween' => [
                        'script' => [
                            'lang' => 'painless',
                            'source' => "doc['x'].value * 1000",
                            'params' => [
                                'x' => 0,
                                'y' => 0,
                                'z' => 0
                            ]
                        ]
                    ]
                ],
                'size' => 10
            ],
        ];
        $response = $this->client->search($params);
        $systems = collect($response['hits']['hits'])->toArray();

        return response()->json($systems);
    }

    public function filter()
    {
        $availableFilters = ['name', 'population', 'allegiance', 'government', 'security', 'economy', 'powers', 'pps', 'has', 'distance', 'has not'];
        $hasFilters = ['stations', 'factions', 'conflicts', 'thargoid'];
        $filterOperators = ['<', '>', '<=', '>=', '='];
        $filterModifiers = ['or', 'and'];
        $searchFilters = [];
        $tmpFilters = [];
        $errors = [];
        $page = request()->input('page') ?? 1;
        $sort = request()->input('sort') ?? 'distance';
        $order = request()->input('order') ?? 'asc';
        $filters = request()->input('filters');
        $abbreviations = ['k' => 1000, 'm' => 1000000, 'b' => 1000000000, 't' => 1000000000000];

        if(!isset($filters)) {
            return response()->json(['error' => 'No filter provided'], 400);
        }

        foreach($availableFilters as $filter) {
            $tmpFilters[] = 'or'.ucFirst($filter);
            $tmpFilters[] = 'and'.ucFirst($filter);
        }

        foreach($filters as $filter)
        {
            $useOr = false;

            if(!strpos($filter, ':')) {
                $errors[] = $filter;
                continue;
            }

            list($key, $value) = explode(':', $filter);
            
            if($key == 'has' && !in_array($value, $hasFilters)) {
                $errors[] = "{$key}:{$value}";
                continue;
            }

            if(strpos($key, 'or') !== false) {
                $key = str_replace('or', '', str_replace(' ', '',lcFirst($key)));
                $useOr = true;
            }

            if(strpos($key, 'and') !== false) {
                $key = str_replace('and', '', str_replace(' ', '',lcFirst($key)));
            }

            if(!in_array($key, $availableFilters)) {
                $errors[] = "{$key}:{$value}";
                continue;
            }

            if (lcFirst($key) == 'population' || lcFirst($key) == 'distance') {
                $value = str_replace(' ', '', $value);
                
                $number = strcspn($value,'0123456789');
                $operator = substr($value, 0, (int)$number);
                $term = substr($value, (int)$number);
                if(is_numeric(substr($value, (int)$number))) {
                    $term = substr($value, (int)$number);
                } else {
                    $term = preg_replace("/,/",".", $term);
                    preg_match_all("/(\d+(?:\.\d+)*)(?:([a-z]+))/", $term, $matches);
                    //dd($term,$matches);
                    $number = $matches[1][0];
                    $abbreviation = $matches[2][0];
                    $term = intVal($number * $abbreviations[strtolower($abbreviation)]);
                }
                $searchFilters[lcFirst($key)] = [
                    'term' => $term,
                    'operator' => $operator,
                    'useOr' => $useOr
                ];
                continue;
            }

            $searchFilters[lcFirst($key)] = [
                'term' => trim($value),
                'useOr' => $useOr
            ];
        }

        //dd($searchFilters);
        
        if(count($errors) > 0) {
            return response()->json(['error' => 'Invalid filter provided', 'filterErrors' => $errors, 'filters' => $availableFilters, 'hasFilters' => $hasFilters, 'filterOperators' => $filterOperators, 'filterModifiers' => $filterModifiers], 400);
        }

        $systems = System::orderBy($sort, $order)->when($searchFilters, function($query) use ($searchFilters) {
            foreach($searchFilters as $key => $value) {
                if ($key == 'population' || $key == 'distance') {
                    if($value['useOr']) {
                        $query->orWhere($key, $value['operator'],$value['term']);
                    } else {
                        $query->where($key, $value['operator'], $value['term']);
                        //dd($value, $query);
                    }
                } else if ($key == 'has') {  
                    if($value['useOr']) {
                        $query->orHas($value['term']);
                    } else {
                        $query->has($value['term']);
                    }
                } else if ($key == 'name') {  
                    if($value['useOr']) {
                        $query->orWhere($key, 'LIKE', "%{$value['term']}%");
                    } else {
                        $query->where($key, 'LIKE', "%{$value['term']}%");
                    }
                } else if ($key == 'economy') {  
                    if($value['useOr']) {
                        $query->orWhere('economy', $value['term'])->orWhere('second_economy', $value['term']);
                    } else {
                        $query->Where('economy', $value['term'])->orWhere('second_economy', $value['term']);
                    }
                } else {
                    if($value['useOr']) {
                        $query->orWhere($key, $value);
                    } else {
                        $query->where($key, $value);
                    }
                }
            }
        })->paginate(25, ['*'], 'page', $page);
        return response()->json(new SystemCollection($systems));
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
    public function show(string $name)
    {
        $system = System::where('name', $name)->with(['faction', 'factions' => function ($q) {
            $q->orderBy('updated_at', 'desc')->groupBy('name');
        }, 'stations', 'stars', 'planets'])->first();

        $system->conflicts()->exists() ? $system->load(['conflicts' => function($query) {
            $query->whereNot('status', '');
        }]) : null;
        $system->thargoid()->exists() ? $system->thargoid : null;

        return response()->json($system);
    }

    public function station(string $system, string $name) {
        $station = System::where('name', $system)->with(['stations' => function ($q) use ($name) {
            $q->where('name', $name)->with(['market' => function ($q) {
                $q->with(['ships', 'modules', 'commodities', 'rareCommodities']);
            }]);
        }])->first();

        return response()->json($station);
    }

    public function stations(string $system) {
        $station = System::where('name', $system)->with(['stations'])->first();

        return response()->json($station);
    }

    public function factions(string $name) {
        $factions = System::where('name', $name)->with(['factions' => function ($q) {
            $q->orderBy('updated_at', 'desc')->groupBy('name');
        }])->first();

        return response()->json($factions);
    }

    public function faction(string $system, string $name) {
        $factions = System::where('name', $system)->with(['factions' => function ($q) use ($name) {
            $q->where('name', $name)->orderBy('updated_at', 'desc');
        }])->first();

        return response()->json($factions);
    }

    public function conflicts(string $name) {
        $conflicts = System::where('name', $name)->with(['conflicts'])->first();

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
