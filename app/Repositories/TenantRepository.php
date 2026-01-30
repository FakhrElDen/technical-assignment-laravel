<?php

namespace App\Repositories;

use App\Models\Tenant;

class TenantRepository
{
    public function __construct(
        protected Tenant $model
    ) {
    }

    /**
     * Find or create a tenant by key.
     */
    public function firstOrCreate(string $key): Tenant
    {
        return $this->model->firstOrCreate(
            ['key' => $key]
        );
    }

    /**
     * Find a tenant by key.
     */
    public function findByKey(string $key): ?Tenant
    {
        return $this->model->where('key', $key)->first();
    }
}

