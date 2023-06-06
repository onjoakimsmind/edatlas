<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;
use JeroenG\Explorer\Application\Explored;

class Star extends Model implements Explored
{
    use HasFactory;
    use Searchable;

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

    // SCOUT
    public function searchableAs()
    {
        return 'stars';
    }

    public function mappableAs(): array
    {
        return [
            'id' => 'keyword',
            'system_id' => 'keyword',
            'body_id' => 'integer',
            'name' => 'text',
            'class' => 'keyword',
            'axial_tilt' => 'float',
            'absolute_magnitude' => 'float',
            'age' => 'float',
            'ascending_node' => 'float',
            'distance_to_arrival' => 'float',
            'eccentricity' => 'float',
            'is_main_star' => 'boolean',
            'is_scoopable' => 'boolean',
            'luminosity' => 'keyword',
            'mean_anomaly' => 'float',
            'orbital_inclination' => 'float',
            'orbital_period' => 'float',
            'radius' => 'float',
            'rotation_period' => 'float',
            'semi_major_axis' => 'float',
            'stellar_mass' => 'float',
            'surface_temperature' => 'float',
            'type' => 'keyword',
            'periapsis' => 'float',
            'parents' => 'text',
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'system_id' => $this->system_id,
            'body_id' => $this->body_id,
            'name' => $this->name,
            'class' => $this->class,
            'axial_tilt' => $this->axial_tilt,
            'absolute_magnitude' => $this->absolute_magnitude,
            'age' => $this->age,
            'ascending_node' => $this->ascending_node,
            'distance_to_arrival' => $this->distance_to_arrival,
            'eccentricity' => $this->eccentricity,
            'is_main_star' => $this->is_main_star,
            'is_scoopable' => $this->is_scoopable,
            'luminosity' => $this->luminosity,
            'mean_anomaly' => $this->mean_anomaly,
            'orbital_inclination' => $this->orbital_inclination,
            'orbital_period' => $this->orbital_period,
            'radius' => $this->radius,
            'rotation_period' => $this->rotation_period,
            'semi_major_axis' => $this->semi_major_axis,
            'stellar_mass' => $this->stellar_mass,
            'surface_temperature' => $this->surface_temperature,
            'type' => $this->type,
            'periapsis' => $this->periapsis,
            'parents' => $this->parents,
        ];
    }

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class, 'system_id');
    }

    public function rings(): HasMany
    {
        return $this->hasMany(Ring::class, 'star_id', 'id');
    }
}
