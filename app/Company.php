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

    /**
     * Grabs the total amount of investments raised for the company.
     *
     * NOTE: This is a true performance killer when done in bulk for
     * hundreds/thousands of companies. Consider porting those bulk
     * listings to an Eloquent Resource.
     *
     * @FIXME: It is -not good- to handle money as floats/strings.
     * @FIXME: Seriously consider using a proper money class, e.g., phpexperts/money-type.
     */
    public function getTotalInvestmentsAttribute(): string
    {
        // This is not the most performant way (e.g., using Laravel Builder with a SQL sum()).
        // But it is the most pragmatic and incurs the least tech debt. Better to use Eloquent
        // Collections and optimize later, as needed.
        $invesmentTotals = $this->investments()->sum('amount');

        // Cast it as a rounded-string to help mitigate floating point imprecision.
        return (string) round($invesmentTotals, 2);
    }
}
