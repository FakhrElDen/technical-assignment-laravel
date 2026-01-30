<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\IndexEventRequest;
use App\Http\Requests\StoreEventRequest;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use Illuminate\Http\JsonResponse;

class EventController extends BaseController
{
    public function __construct(
        protected EventService $eventService
    ) {
    }

    /**
     * Store a new event (with idempotency).
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        try {
            $result = $this->eventService->storeEvent($request->validated());

            $message = $result['created'] ? 'Event created successfully' : 'Event already exists';
            $statusCode = $result['created'] ? 201 : 200;

            return $this->successResponse(
                new EventResource($result['event']),
                $message,
                $statusCode
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to store event', 500, $e->getMessage());
        }
    }

    /**
     * Retrieve events with filters.
     */
    public function index(IndexEventRequest $request): JsonResponse
    {
        try {
            $result = $this->eventService->getEvents(
                $request->input('tenant_key'),
                $request->input('device_uid'),
                $request->input('type')
            );

            return $this->successResponse([
                'events' => EventResource::collection($result['events']),
                'count' => $result['count'],
            ], 'Events retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve events', 500, $e->getMessage());
        }
    }
}


