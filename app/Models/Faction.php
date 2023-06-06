<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;
use JeroenG\Explorer\Application\Explored;


class Faction extends Model implements Explored
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'name',
        'allegiance',
        'government',
        'influence',
        'state',
        'happiness',
        'active_states',
        'pending_states',
        'recovering_states',
        'updated_at',
    ];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';

    // SCOUT ELASTIC
    public function searchableAs()
    {
        return 'factions';
    }

    public function mappableAs(): array
    {
        return [
            'id' => 'integer',
            'system_id' => 'integer',
            'name' => [
                'type' => 'text',
                'fields' => [
                    'keyword' => [
                        'type' => 'keyword',
                        'ignore_above' => 256,
                    ],
                ]
            ],
            'allegiance' => 'keyword',
            'government' => 'keyword',
            'state' => 'text',
            'influence' => 'float',
            'active_states' => 'text',
            'pending_states' => 'text',
            'recovering_states' => 'text',
            'landing_pads' => 'text',
            'updated_at' => 'date',
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'system_id' => $this->system_id,
            'allegiance' => $this->allegiance,
            'government' => $this->government,  
            'state' => $this->state,
            'influence' => $this->influence,
            'active_states' => explode(",",$this->active_states),
            'pending_states' => explode(",",$this->pending_states),
            'recovering_states' => explode(",",$this->recovering_states),
            'updated_at' => $this->updated_at,
        ];
    }

    public function systems(): BelongsToMany
    {
        return $this->belongsToMany(System::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(FactionHistory::class, 'faction_id', 'id')->select('id', 'faction_id','influence', 'updated_at');
    }

}
