<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThargoidWar extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_id',
        'current_state',
        'days_remaining',
        'success_state',
        'failure_state',
        'remaining_ports',
        'success_reached',
        'war_progress',
        'updated_at',
    ];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';
}
