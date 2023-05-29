<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TradeRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_market_id',
        'to_market_id',
        'commodity',
        'buy_price',
        'sell_price',
        'profit',
        'distance',
    ];

    protected $hidden = [
        'id',
        'from_market_id',
        'to_market_id',
    ];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';

    public function from() : HasOne
    {
        return $this->hasOne(Market::class, 'id', 'from_market_id');
    }

    public function to() : HasOne
    {
        return $this->hasOne(Market::class, 'id', 'to_market_id');
    }
}
