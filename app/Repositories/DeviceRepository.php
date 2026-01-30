<?php

namespace App\Repositories;

use App\Models\Device;

class DeviceRepository
{
    public function __construct(
        protected Device $model
    ) {
    }

    /**
     * Find or create a device by tenant ID and device UID.
     */
    public function firstOrCreate(int $tenantId, string $deviceUid): Device
    {
        return $this->model->firstOrCreate([
            'tenant_id' => $tenantId,
            'device_uid' => $deviceUid,
        ]);
    }

    /**
     * Find a device by tenant ID and device UID.
     */
    public function findByTenantAndUid(int $tenantId, string $deviceUid): ?Device
    {
        return $this->model->where('tenant_id', $tenantId)
            ->where('device_uid', $deviceUid)
            ->first();
    }

    /**
     * Find a device by ID.
     */
    public function findById(int $id): ?Device
    {
        return $this->model->find($id);
    }
}

