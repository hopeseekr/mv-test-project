<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Company extends Model
{
    public function investments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Investment::class,
            Investor::class,
            'id',
            'company_id'
        );
    }
}
