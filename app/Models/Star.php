<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Star extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_id',
        'body_id',
        'name',
        'class',
        'axial_tilt',
        'absolute_magnitude',
        'age',
        'ascending_node',
        'distance_to_arrival',
        'eccentricity',
        'is_main_star',
        'is_scoopable',
        'luminosity',
        'mean_anomaly',
        'orbital_inclination',
        'orbital_period',
        'radius',
        'rotation_period',
        'semi_major_axis',
        'stellar_mass',
        'surface_temperature',
        'type',
        'periapsis',
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
        return $this->hasMany(Ring::class, 'star_id', 'id');
    }
}
