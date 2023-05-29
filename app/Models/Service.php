<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_id',
        'name',
    ];

    public $timestamps = ['updated_at'];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';
}
