<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investment extends Model
{
    /**
     * An investment belongs to an Investor
     *
     * @return BelongsTo
     */
    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
