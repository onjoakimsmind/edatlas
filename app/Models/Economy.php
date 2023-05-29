<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Economy extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_id',
        'name',
        'proportion',
    ];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';
}
