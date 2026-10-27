<?php

declare(strict_types=1);

namespace Rimba\Asset\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Rimba\Asset\Enums\AssetEventCategory;
use Rimba\Asset\Events\AssetEventRecorded;
use Rimba\Asset\Models\Asset;
use Rimba\Asset\Models\AssetEvent;

final class RecordAssetEvent
{
    /** @param array<string, mixed> $attributes */
    public function handle(Asset $asset, array $attributes): AssetEvent
    {
        return DB::transaction(function () use ($asset, $attributes): AssetEvent {
            $category = $attributes['category'] ?? null;
            $type = trim((string) ($attributes['type'] ?? ''));

            if (! $category || $type === '') {
                throw new InvalidArgumentException('Asset event category and type are required.');
            }

            $category = $category instanceof AssetEventCategory
                ? $category
                : AssetEventCategory::from((string) $category);

            if ($category === AssetEventCategory::Metering && ! array_key_exists('value', $attributes)) {
                throw new InvalidArgumentException('Metering events require a value.');
            }

            /** @var AssetEvent $model */
            $model = $asset->events()->create([
                ...Arr::only($attributes, [
                    'description', 'value', 'unit', 'data',
                    'reference_type', 'reference_id', 'recorded_by',
                ]),
                'category' => $category,
                'type' => $type,
                'occurred_at' => $attributes['occurred_at'] ?? now(),
            ]);

            AssetEventRecorded::dispatch($model);

            return $model;
        });
    }
}
