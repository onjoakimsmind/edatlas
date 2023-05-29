<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;
use JeroenG\Explorer\Application\Explored;

class System extends Model implements Explored
{
    use HasFactory;
    use Searchable;

    public $asYouType = true;

    protected $fillable = [
        'name',
        'address',
        'x',
        'y',
        'z',
        'distance',
        'faction_id',
        'economy',
        'second_economy',
        'allegiance',
        'government',
        'security',
        'population',
        'powers',
        'pps',
        'updated_at',
    ];

    protected $hidden = [
        'faction_id'
    ];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';

    // SCOUT

    public function searchableAs()
    {
        return 'systems';
    }

    public function mappableAs(): array
    {
        return [
            'id' => 'keyword',
            'name' => [
                'type' => 'text',
                'fields' => [
                    'keyword' => [
                        'type' => 'keyword',
                        'ignore_above' => 256,
                    ],
                ]
            ],
            'address' => 'keyword',
            'x' => 'float',
            'y' => 'float',
            'z' => 'float',
            'distance' => 'float',
            'faction_id' => 'integer',
            'economy' => 'keyword',
            'second_economy' => 'keyword',
            'allegiance' => 'keyword',
            'government' => 'keyword',
            'security' => 'keyword',
            'population' => 'long',
            'powers' => 'text',
            'pps' => 'text',
            'updated_at' => 'date',
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'x' => $this->x,
            'y' => $this->y,
            'z' => $this->z,
            'distance' => $this->distance,
            'faction_id' => $this->faction_id,
            'economy' => $this->economy,
            'second_economy' => $this->second_economy,
            'allegiance' => $this->allegiance,
            'government' => $this->government,
            'security' => $this->security,
            'population' => $this->population,
            'powers' => $this->powers,
            'pps' => $this->pps,
            'updated_at' => $this->updated_at,
        ];
    }

    // RELATIONSHIPS
    public function faction() : HasOne
    {
        return $this->hasOne(Faction::class, 'id', 'faction_id');
    }

    public function factions() : BelongsToMany
    {
        return $this->belongsToMany(Faction::class);
    }

    public function stations() : HasMany
    {
        return $this->hasMany(Station::class, 'system_id', 'id');
    }

    public function conflicts() : HasMany
    {
        return $this->hasMany(Conflict::class, 'system_id', 'id')->orderBy('id', 'desc');
    }

    public function stars(): HasMany
    {
        return $this->hasMany(Star::class, 'system_id', 'id');
    }

    public function planets(): HasMany
    {
        return $this->hasMany(Planet::class, 'system_id', 'id');
    }

    public function thargoid(): HasOne {
        return $this->hasOne(ThargoidWar::class, 'system_id', 'id');
    }
}
