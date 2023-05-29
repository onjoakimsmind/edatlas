<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Providers\ED;
use App\Models\Commodity;
use App\Models\System;

class MarketController extends Controller
{
    public function trade(Request $request)
    {
        $origin = System::select('id','x','y','z')->where('name', $request->data['system']['name'])->first();
        $maxDistance = 10;
        $systems = System::where('id', '!=', $origin->id)->where(DB::raw("distance($origin->id, id)", '<', $maxDistance))
        ->get();
        dd($systems);
        return response()->json($routes);
    }
}
