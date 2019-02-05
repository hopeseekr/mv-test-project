<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investor extends Model
{
    public function investment(): HasMany
    {
        return $this->hasMany(Investment::class, 'investor_id');
    }
}
