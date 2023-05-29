<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'faction_id',
        'system_id',
        'influence',
    ];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';
}
