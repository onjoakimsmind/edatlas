<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Providers\ED;

use Elasticsearch\ClientBuilder;

class SearchController extends Controller
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

    public function __invoke(Request $request)
    {
        $query = $request->input('q');

        $results = [
            'systems' => [],
            'stations' => [],
            'factions' => [],
            'commodities' => [],
        ];

        $params = [
            'index' => 'systems',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'wildcard' => [
                                'name' => "{$request->q}*"
                            ]
                        ]
                    ]
                ],
                'size' => 10
            ],
        ];
        $response = $this->client->search($params);
        $systems = collect($response['hits']['hits'])->pluck('_source')->toArray();
        $results['stations'] = \App\Models\Station::select('name', 'market_id', 'system_id')->where('name', 'LIKE', "{$query}%")->with(['system' => function($q) {
            $q->select('id', 'name')->pluck('name');
        }])->limit(10)->get();
        $results['systems'] = \App\Models\System::select('id', 'name')->where('name', 'LIKE', "{$query}%")->limit(20)->get();
        $results['factions'] = \App\Models\Faction::select('id', 'name')->where('name', 'LIKE', "{$query}%")->groupBy('name')->orderBy('id','DESC')->limit(20)->get();
        $results['commodities'] = collect($this->searchForName($query, array_merge(ED::$commodity, ED::$rare)));

        return response()->json($results);
    }

    private function searchForName($name, $array) {
        $results = [];
        foreach ($array as $key => $val) {
            //var_dump($val['name'], str_starts_with(strtolower($val['name']),strtolower($name)));
            if (!str_starts_with(strtolower($val['name']),strtolower($name))) {
                continue;
            }
            $results[] = ['name' => $val['name']];
        }
        return $results;
    }
}
