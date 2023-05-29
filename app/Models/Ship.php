<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ship extends Model
{
    use HasFactory;

    protected $fillable = [
        'market_id',
        'name',
        'symbol'
    ];

    protected $hidden = [
        'id',
        'market_id',
    ];

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';

    public function market()
    {
        return $this->belongsTo(Market::class);
    }
}
