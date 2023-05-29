<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conflict extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_id',
        'faction1',
        'faction2',
        'faction1_stake',
        'faction2_stake',
        'faction1_days_won',
        'faction2_days_won',
        'war_type',
        'status',
    ];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';
}
