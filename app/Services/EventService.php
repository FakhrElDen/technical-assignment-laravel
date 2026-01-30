<?php

namespace App\Services;

use App\Jobs\ProcessEventCreated;
use App\Repositories\DeviceRepository;
use App\Repositories\EventRepository;
use App\Repositories\TenantRepository;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function __construct(
        protected TenantRepository $tenantRepository,
        protected DeviceRepository $deviceRepository,
        protected EventRepository $eventRepository
    ) {
    }

    /**
     * Store a new event with idempotency.
     */
    public function storeEvent(array $data): array
    {
        return DB::transaction(function () use ($data) {
            // 1. Resolve tenant
            $tenant = $this->tenantRepository->firstOrCreate(
                $data['tenant_key'],
                $data['tenant_key']
            );

            // 2. Resolve or create device
            $device = $this->deviceRepository->firstOrCreate(
                $tenant->id,
                $data['device_uid']
            );

            // 3. Attempt to create event (idempotency via unique constraint)
            $event = $this->eventRepository->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'event_uid' => $data['event_uid'],
                ],
                [
                    'device_id' => $device->id,
                    'type' => $data['type'],
                    'occurred_at' => $data['occurred_at'],
                    'payload' => $data['payload'],
                ]
            );

            // 4. Dispatch background job to process the event
            if ($event->wasRecentlyCreated) {
                ProcessEventCreated::dispatch($device->id, $data['occurred_at']);
            }

            return [
                'event' => $event,
                'created' => $event->wasRecentlyCreated,
            ];
        });
    }

    /**
     * Get events with filters.
     */
    public function getEvents(?string $tenantKey = null, ?string $deviceUid = null, ?string $type = null): array
    {
        $events = $this->eventRepository->getFiltered($tenantKey, $deviceUid, $type);

        return [
            'events' => $events,
            'count' => $events->count(),
        ];
    }
}

