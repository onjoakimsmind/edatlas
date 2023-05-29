<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Providers\ED;
use App\Models\Commodity;
use App\Models\TradeRoute;
use App\Models\Station;
use App\Models\System;

class GenerateTradeRoute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edsa:generate-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate trade routes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating trade route matrix');
        $data = array_keys(ED::$commodity);
        $dist = 80;
        for($c = 0; $c < count($data); ++$c) {
            $start = now();
            $symbol = $data[$c];
            $from = Commodity::where('symbol', $symbol)->with(['market' => function($q){
                $q->with(['station' => function($q1){
                    $q1->whereNotIn('type', ['', 'FleetCarrier', 'Drake-Class Carrier'])->orWhereNot('type', null)->with('system');
                }]);
            }])->without(['rareCommodities', 'ships', 'modules', 'prohibited'])->get();
            dd(count($from));
            $to = clone $from;
            $this->info("Generating trade routes for {$symbol}");
            for($i = 0; $i < count($from); ++$i)
            {
                $fromStart = now();

                if(!$from[$i]->market) {
                    continue;
                }

                if(!$from[$i]->market->station) {
                    continue;
                }

                if($from[$i]->market->station) {
                    if(!$from[$i]->market->station->system_id) {
                        var_dump($from[$j]->market_id);
                        continue;
                    }
                }

                $fromSystem = $from[$i]->market->station->system_id;
                for($j = 0; $j < count($to); ++$j)
                {
                    if($from[$i]->market_id == $to[$j]->market_id) {
                        continue;
                    }

                    if(!$to[$j]->market) {
                        continue;
                    }

                    if(!$to[$j]->market->station) {
                        continue;
                    }

                    if($to[$j]->market->station) {
                        if(!$to[$j]->market->station->system_id) {
                            continue;
                        }
                    }
                    $toSystem = $to[$j]->market->station->system_id;
                    $distance = ED::distanceBetweenXYZ($fromSystem->x, $fromSystem->y, $fromSystem->z, $toSystem->x, $toSystem->y, $toSystem->z);
                    if($distance > $dist) {
                        continue;
                    }
                    $this->comment("Generating from {$from[$i]->market->station->name} to {$to[$j]->market->station->name}");
                    $profit = $to[$j]->sell_price - $from[$i]->buy_price;

                    $tradeRoute = TradeRoute::where('from_market_id', $from[$i]->market_id)->where('to_market_id', $to[$j]->market_id)->where('commodity', $symbol)->first();
                    if(!$tradeRoute) {
                        $tradeRoute = new TradeRoute();
                        $tradeRoute->from_market_id = $from[$i]->market_id;
                        $tradeRoute->to_market_id = $to[$j]->market_id;
                        $tradeRoute->commodity = $symbol;
                        $tradeRoute->buy_price = $from[$i]->buy_price;
                        $tradeRoute->sell_price = $to[$j]->sell_price;
                        $tradeRoute->distance = $distance;
                        $tradeRoute->profit = $profit;
                        $tradeRoute->save();
                    }
                }
                $time = now()->diffInSeconds($fromStart);
                $this->info("{$time} seconds");
            }
            $time = now()->diffInSeconds($start);
            $this->info("{$time} seconds");
        }
        /*$from = Commodity::where('symbol', $symbol)->where('buy_price', '>', 0)->get();
            foreach($from as $key => $from) {
                $to = Commodity::where('symbol', $symbol)->get();
                foreach($to as $key => $to) {
                    $fromSystem = Station::where('market_id', $from->market_id)->first();
                    $toSystem = Station::where('market_id', $to->market_id)->first();
                    $distance = ED::distanceBetween($fromSystem->system_id, $toSystem->system_id);
                    if($distance > 100) {
                        continue;
                    }
                    $profit = $to->sell_price - $from->buy_price;

                    $tradeRoute = TradeRoute::where('from_market_id', $from->market_id)->where('to_market_id', $to->market_id)->first();
                    if(!$tradeRoute) {
                        $tradeRoute = new TradeRoute();
                        $tradeRoute->from_market_id = $from->market_id;
                        $tradeRoute->to_market_id = $to->market_id;
                        $tradeRoute->commodity = $from->symbol;
                        $tradeRoute->buy_price = $from->buy_price;
                        $tradeRoute->sell_price = $to->sell_price;
                        $tradeRoute->distance = $distance;
                        $tradeRoute->profit = $profit;
                        $tradeRoute->save();
                    }
                }
            }*/
        /*foreach($data as $symbol => $commodity) {
            
        }*/
    }
}
