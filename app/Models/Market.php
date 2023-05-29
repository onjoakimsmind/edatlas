<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class Market extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'station_id',
    ];

    protected $hidden = [
        'id',
        'station_id',
        'market_id',
    ];

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $with = ['station', 'ships', 'modules', 'commodities', 'rareCommodities', 'prohibited'];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    public function station() : belongsTo
    {
        return $this->belongsTo(Station::class, 'station_id', 'id');
    }

    public function ships() : HasMany
    {
        return $this->hasMany(Ship::class, 'market_id', 'id');
    }

    public function modules() : HasMany
    {
        return $this->hasMany(Module::class, 'market_id', 'id');
    }

    public function commodities() : HasMany
    {
        return $this->hasMany(Commodity::class, 'market_id', 'id');
    }

    public function rareCommodities() : HasMany
    {
        return $this->hasMany(RareCommodity::class, 'market_id', 'id');
    }

    public function prohibited() : HasMany
    {
        return $this->hasMany(Prohibited::class, 'market_id', 'id');
    }

    public function history() : HasMany
    {
        return $this->hasMany(Commodity::class, 'market_id', 'id');
    }
}
