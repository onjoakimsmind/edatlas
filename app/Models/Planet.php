<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Planet extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_id',
        'body_id',
        'name',
        'atmosphere_type',
        'atmosphere_composition',
        'ascending_node',
        'axial_tilt',
        'composition',
        'class',
        'distance_to_arrival',
        'eccentricity',
        'gravity',
        'is_landable',
        'mass',
        'mean_anomaly',
        'orbital_inclination',
        'orbital_period',
        'radius',
        'rotation_period',
        'semi_major_axis',
        'surface_pressure',
        'surface_temperature',
        'tidally_locked',
        'terraforming_state',
        'volcanism',
        'parents',
    ];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';

    protected $with = ['rings'];

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class, 'system_id');
    }

    public function rings(): HasMany
    {
        return $this->hasMany(Ring::class, 'planet_id', 'id');
    }
}
