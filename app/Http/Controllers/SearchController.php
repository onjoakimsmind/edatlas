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

        $systemParams = [
            'index' => 'systems',
            'body' => [
                'query' => [
                    'match_phrase_prefix' => [
                        'name' => [
                            'query' => $query,
                        ]
                    ]
                ],
                'size' => 10,
            ],
        ];

        $stationParams = [
            'index' => 'stations',
            'body' => [
                'query' => [
                    'match_phrase_prefix' => [
                        'name' => [
                            'query' => $query,
                        ]
                    ]
                ],
                'size' => 10,
            ],
        ];

        $factionParams = [
            'index' => 'factions',
            'body' => [
                'query' => [
                    'match_phrase_prefix' => [
                        'name' => [
                            'query' => $query,
                        ]
                    ]
                ],
                'size' => 10,
            ],
        ];

        $sysResponse = $this->client->search($systemParams);
        $systems = collect($sysResponse['hits']['hits'])->pluck('_source');

        $staResponse = $this->client->search($stationParams);
        $stations = collect($staResponse['hits']['hits'])->pluck('_source');

        $facResponse = $this->client->search($factionParams);
        $factions = collect($facResponse['hits']['hits'])->pluck('_source');
        
        $results['stations'] = $stations->map(function($item, $key) {
            $response = $this->client->get([
                'index' => 'systems',
                'id' => $item['system_id'],
            ]);
            $system = $response['_source'];
            return [
                'name' => $item['name'],
                'market_id' => $item['market_id'],
                'system_id' => $item['system_id'],
                'system' => [
                    'name' => $system['name'],
                    'id' => $item['system_id'],
                ],
            ];
        });
        $results['systems'] = $systems;
        $results['factions'] = $factions;
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
