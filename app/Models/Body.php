<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Body extends Model
{
    use HasFactory;

    public function stars(): HasMany
    {
        return $this->hasMany(Star::class, 'body_id', 'id');
    }
}
