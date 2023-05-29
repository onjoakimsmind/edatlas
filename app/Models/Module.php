<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'market_id',
        'name',
        'symbol',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'market_id',
    ];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';
}
