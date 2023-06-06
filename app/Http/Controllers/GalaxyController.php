<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Elasticsearch\ClientBuilder;

use App\Models\System;

class GalaxyController extends Controller
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

    public function galaxy(Request $request)
    {
        $x = $request->x ?? 0;
        $y = $request->y ?? 0;
        $z = $request->z ?? 0;

        $systems = System::orderBy('x', 'asc')->orderBy('y', 'asc')->orderBy('z', 'asc')->cursorPaginate(1000);

        return response()->json(['systems' => $systems]);
    }

    public function index(Request $request)
    {
        $x = $request->x ?? 0;
        $y = $request->y ?? 0;
        $z = $request->z ?? 0;
        $range = 100;

        $xPoslte = $x < 0 ? $x - $range : $x + $range;
        $yPoslte = $y < 0 ? $y - $range : $y + $range;
        $zPoslte = $z < 0 ? $z - $range : $z + $range;
        $xPosgte = $x < 0 ? $x + $range : $x - $range;
        $yPosgte = $y < 0 ? $y + $range : $y - $range;
        $zPosgte = $z < 0 ? $z + $range : $z - $range;



        /*$params = [
            'index' => 'systems',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [ 'range' => [ 'x' => [ $this->ltegte($xPosgte, $xPoslte) => $xPoslte, $this->ltegte($xPoslte, $xPosgte) => $xPosgte ] ] ],
                            [ 'range' => [ 'y' => [ $this->ltegte($yPosgte, $yPoslte) => $yPoslte, $this->ltegte($yPoslte, $yPosgte) => $yPosgte ] ] ],
                            [ 'range' => [ 'z' => [ $this->ltegte($zPosgte, $zPoslte) => $zPoslte, $this->ltegte($zPoslte, $zPosgte) => $zPosgte ] ] ]
                        ]
                    ]
                ],
                '_source' => ['name', 'x', 'y', 'z', 'distance'],
                'sort' => [
                    [ 'distance' => [ 'order' => 'asc' ] ]
                ],
            ],
            'scroll' => '1m',
            'size' => 1000
        ];*/
        $params = [
            'index' => 'systems',
            'body' => [
                'query' => [
                    'range' => [
                        'distance' => [
                            'lte' => 250
                        ]
                    ]
                ]
            ],
            'scroll' => '1m',
            'size' => 10000
        ];
        $response = $this->client->search($params);
        $systems = collect($response['hits']['hits'])->pluck('_source')->toArray();

        return response()->json(['systems' => $systems, 'scroll' => $response['_scroll_id']]);
    }

    public function scroll(Request $request)
    {
        $scroll = $request->scroll ?? null;
        if (!$scroll) {
            return response()->json(['systems' => [], 'scroll' => null]);
        }
        $response = $this->client->scroll([
            'body' => [
                'scroll_id' => $scroll,  //...using our previously obtained _scroll_id
                'scroll'    => '1m'        // and the same timeout window
            ]
        ]);

        $systems = collect($response['hits']['hits'])->pluck('_source')->toArray();

        $scrollId = count($response['hits']['hits']) ? $response['_scroll_id'] : null;
        if(is_null($scrollId)) {
            $this->client->clearScroll(['scroll_id' => $scroll]);
        }
        return response()->json(['systems' => $systems, 'scroll' => $scrollId]);
    }

    public function grid()
    {
        $params = [
            'index' => 'systems',
            'body' => [
                'aggs' => [
                    'max_x_coord' => [ 'max' => [ 'field' => 'x' ] ],
                    'min_x_coord' => [ 'min' => [ 'field' => 'x' ] ],
                    'max_y_coord' => [ 'max' => [ 'field' => 'z' ] ],
                    'min_y_coord' => [ 'min' => [ 'field' => 'z' ] ],
                ],
            ],
            'size' => 0
        ];

        $response = $this->client->search($params);
        $grid = collect($response['aggregations']);

        return response()->json($grid);
    }

    private function ltegte($val1, $val2): string
    {
        return $val1 < $val2 ? 'lte' : 'gte';
    }
}
