<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EventRepository
{
    public function __construct(
        protected Event $model
    ) {
    }

    /**
     * Find or create an event.
     */
    public function firstOrCreate(array $uniqueAttributes, array $additionalAttributes): Event
    {
        return $this->model->firstOrCreate($uniqueAttributes, $additionalAttributes);
    }

    /**
     * Get events with filters.
     */
    public function getFiltered(?string $tenantKey = null, ?string $deviceUid = null, ?string $type = null): LengthAwarePaginator
    {
        $query = $this->model->query()->with(['tenant', 'device']);

        // Filter by tenant_key
        if ($tenantKey) {
            $query->whereHas('tenant', function ($q) use ($tenantKey) {
                $q->where('key', $tenantKey);
            });
        }

        // Filter by device_uid
        if ($deviceUid) {
            $query->whereHas('device', function ($q) use ($deviceUid) {
                $q->where('device_uid', $deviceUid);
            });
        }

        // Filter by type
        if ($type) {
            $query->where('type', $type);
        }

        return $query->orderBy('occurred_at', 'desc')->paginate(2);
    }
}

