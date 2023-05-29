<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ring extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_id',
        'planet_id',
        'star_id',
        'name',
        'type',
        'symbol',
        'mass',
        'inner_radius',
        'outer_radius',
    ];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';
}
