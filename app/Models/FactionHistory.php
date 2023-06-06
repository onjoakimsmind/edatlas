<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'faction_id',
        'system_id',
        'influence',
    ];

    protected $hidden = [
        'id',
        'faction_id',
        'system_id',
    ];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';
}
