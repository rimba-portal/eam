<?php

declare(strict_types=1);

namespace Rimba\Asset\Services;

use Rimba\Asset\Actions\RecordAssetEvent;
use Rimba\Asset\Enums\AssetEventCategory;
use Rimba\Asset\Models\Asset;
use Rimba\Asset\Models\AssetEvent;

final readonly class AssetEventService
{
    public function __construct(private RecordAssetEvent $recordAssetEvent) {}

    /** @param array<string, mixed> $data */
    public function lifecycle(Asset $asset, string $type, array $data = []): AssetEvent
    {
        return $this->recordAssetEvent->handle($asset, [...$data, 'category' => AssetEventCategory::Lifecycle, 'type' => $type]);
    }

    /** @param array<string, mixed> $data */
    public function maintenance(Asset $asset, string $type, array $data = []): AssetEvent
    {
        return $this->recordAssetEvent->handle($asset, [...$data, 'category' => AssetEventCategory::Maintenance, 'type' => $type]);
    }

    /** @param array<string, mixed> $data */
    public function reliability(Asset $asset, string $type, array $data = []): AssetEvent
    {
        return $this->recordAssetEvent->handle($asset, [...$data, 'category' => AssetEventCategory::Reliability, 'type' => $type]);
    }

    /** @param array<string, mixed> $data */
    public function meter(Asset $asset, string $meter, float|int|string $value, ?string $unit = null, array $data = []): AssetEvent
    {
        return $this->recordAssetEvent->handle($asset, [
            ...$data,
            'category' => AssetEventCategory::Metering,
            'type' => $meter,
            'value' => $value,
            'unit' => $unit,
        ]);
    }
}
