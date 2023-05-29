<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RareCommodity extends Model
{
    use HasFactory;

    protected $fillable = [
        'market_id',
        'name',
        'symbol',
        'buy_price',
        'sell_price',
        'demand',
        'stock',
        'demand_bracket',
        'stock_bracket',
        'updated_at'
    ];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';
}
