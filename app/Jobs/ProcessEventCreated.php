<?php

namespace App\Jobs;

use App\Repositories\DeviceRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessEventCreated implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $deviceId,
        public string $occurredAt
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(DeviceRepository $deviceRepository): void
    {
        // Update the device's last_event_at timestamp
        $device = $deviceRepository->findById($this->deviceId);

        if ($device) {
            $device->last_event_at = Carbon::parse($this->occurredAt);
            $device->save();
        }
    }
}
