<?php

namespace App\Traits;

use App\Models\Scopes\CompanyScope;

trait BelongsToCompany
{
    /**
     * The "booted" method of the model.
     */
    protected static function bootBelongsToCompany(): void
    {
        static::addGlobalScope(new CompanyScope);

        static::creating(function ($client) {
            if (session()->has('company_id')) {
                $client->company_id = session('company_id');
            }
        });

        static::updating(function ($client) {
            if (session()->has('company_id')) {
                $client->company_id = session('company_id');
            }
        });

        static::deleting(function ($client) {
            if (session()->has('company_id')) {
                $client->company_id = session('company_id');
            }
        });
    }
}