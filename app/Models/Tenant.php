<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $guarded = [];

    /**
     * Get the devices for the tenant.
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Get the events for the tenant.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
