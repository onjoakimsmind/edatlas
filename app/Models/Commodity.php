<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commodity extends Model
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

    protected $hidden = [
        'id',
        'market_id',
    ];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';

    public function market() : BelongsTo
    {
        return $this->belongsTo(Market::class);
    }
}
