<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use JeroenG\Explorer\Application\Explored;

class Station extends Model implements Explored
{
    use HasFactory;
    use Searchable;
    
    protected $fillable = [
        'system_id',
        'market_id',
        'name',
        'type',
        'distance_to_arrival',
        'allegiance',
        'government',
        'economy',
        'state',
        'landing_pads',
        'faction_id',
        'updated_at'
    ];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';

    // SCOUT ELASTIC
    public function mappableAs(): array
    {
        return [
            'system_id' => 'integer',
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
            'type' => 'keyword',
            'distance_to_arrival' => 'float',
            'allegiance' => 'keyword',
            'government' => 'keyword',
            'economy' => 'keyword',
            'state' => 'keyword',
            'landing_pads' => 'text',
            'faction_id' => 'integer',
            'updated_at' => 'date',
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name.keyword' => $this->name,
            'place' => $this->place,
            'lifespan' => $this->lifespan,
            'type' => $this->type,
            'distance_to_arrival' => $this->distance_to_arrival,
            'allegiance' => $this->allegiance,
            'government' => $this->government,
            'economy' => $this->economy,
            'state' => $this->state,
            'landing_pads' => $this->landing_pads,
            'faction_id' => $this->faction_id,
            'updated_at' => $this->updated_at,
        ];
    }

    // RELATIONSHIPS

    public function economies() : HasMany
    {
        return $this->hasMany(Economy::class, 'station_id', 'id');
    }

    public function services() : HasMany
    {
        return $this->hasMany(Service::class, 'station_id', 'id');
    }

    public function market() : HasOne
    {
        return $this->hasOne(Market::class, 'station_id', 'id');
    }

    public function system() : BelongsTo
    {
        return $this->belongsTo(System::class, 'system_id', 'id');
    }
}
