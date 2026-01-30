<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Event;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a tenant
        $tenant = Tenant::create([
            'key' => 'acme',
            'name' => 'ACME Corporation',
        ]);

        // Create devices
        $device1 = Device::create([
            'tenant_id' => $tenant->id,
            'device_uid' => 'DEV-001',
        ]);

        $device2 = Device::create([
            'tenant_id' => $tenant->id,
            'device_uid' => 'DEV-002',
        ]);

        // Create events
        Event::create([
            'tenant_id' => $tenant->id,
            'device_id' => $device1->id,
            'event_uid' => 'evt_000001',
            'type' => 'location',
            'occurred_at' => '2026-01-28T08:12:11Z',
            'payload' => [
                'lat' => 48.1486,
                'lng' => 17.1077,
                'accuracy' => 12,
            ],
        ]);

        Event::create([
            'tenant_id' => $tenant->id,
            'device_id' => $device1->id,
            'event_uid' => 'evt_000002',
            'type' => 'battery',
            'occurred_at' => '2026-01-28T09:00:00Z',
            'payload' => [
                'level' => 75,
            ],
        ]);

        Event::create([
            'tenant_id' => $tenant->id,
            'device_id' => $device2->id,
            'event_uid' => 'evt_000003',
            'type' => 'location',
            'occurred_at' => '2026-01-28T10:30:00Z',
            'payload' => [
                'lat' => 40.7128,
                'lng' => -74.0060,
                'accuracy' => 8,
            ],
        ]);
    }
}
