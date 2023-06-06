<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;
use JeroenG\Explorer\Application\Explored;

class Commodity extends Model implements Explored
{
    use HasFactory;
    use Searchable;

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

    // SCOUT ELASTIC
    public function searchableAs()
    {
        return 'commodities';
    }

    public function mappableAs(): array
    {
        return [
            'market_id' => 'long',
            'name' => [
                'type' => 'text',
                'fields' => [
                    'keyword' => [
                        'type' => 'keyword',
                        'ignore_above' => 256,
                    ],
                ]
            ],
            'name' => 'keyword',
            'buy_price' => 'integer',
            'sell_price' => 'integer',
            'demand' => 'integer',
            'stock' => 'integer',
            'updated_at' => 'date',
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'market_id' => $this->market_id,
            'name' => $this->name,
            'symbol' => $this->symbol,
            'buy_price' => $this->buy_price,
            'sell_price' => $this->sell_price,
            'demand' => $this->demand,
            'stock' => $this->stock,
            'updated_at' => $this->updated_at,
        ];
    }

    public function market() : BelongsTo
    {
        return $this->belongsTo(Market::class);
    }
}
