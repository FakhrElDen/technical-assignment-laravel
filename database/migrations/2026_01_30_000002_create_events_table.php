<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->string('event_uid'); // e.g., "evt_000001"
            $table->string('type'); // e.g., "location", "battery", "status"
            $table->timestamp('occurred_at');
            $table->json('payload'); // Dynamic JSON payload
            $table->timestamps();

            // Critical: Prevent duplicate events (idempotency)
            $table->unique(['tenant_id', 'event_uid']);

            // Indexes for GET filters
            $table->index('tenant_id');
            $table->index('device_id');
            $table->index('type');
            $table->index(['tenant_id', 'type']); // Composite index
            $table->index(['tenant_id', 'device_id']); // Composite index
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
