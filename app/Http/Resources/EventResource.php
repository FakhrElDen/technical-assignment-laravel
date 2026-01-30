<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event_uid' => $this->event_uid,
            'type' => $this->type,
            'occurred_at' => $this->occurred_at->toIso8601String(),
            'payload' => $this->payload,
            'created_at' => $this->created_at->toIso8601String(),
            'tenant' => new TenantResource($this->whenLoaded('tenant')),
            'device' => new DeviceResource($this->whenLoaded('device')),
        ];
    }

}
