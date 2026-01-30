<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    protected $guarded = [];

    protected $casts = [
        'last_event_at' => 'datetime',
    ];

    /**
     * Get the tenant that owns the device.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the events for the device.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
