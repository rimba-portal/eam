<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->string('category', 40)->index();
            $table->string('type', 100)->index();
            $table->timestampTz('occurred_at')->index();
            $table->text('description')->nullable();
            $table->decimal('value', 24, 6)->nullable();
            $table->string('unit', 40)->nullable();
            $table->json('data')->nullable();
            $table->nullableMorphs('reference');
            $table->unsignedBigInteger('recorded_by')->nullable()->index();
            $table->timestamps();

            $table->index(['asset_id', 'category', 'type', 'occurred_at'], 'asset_event_timeline_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_events');
    }
};
