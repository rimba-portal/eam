<?php

declare(strict_types=1);

namespace Rimba\Asset\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Packstub\Flow\Concerns\HasWorkflows;
use Rimba\Asset\Enums\AssetEventCategory;

#[Fillable([
    'asset_id',
    'category',
    'type',
    'occurred_at',
    'description',
    'value',
    'unit',
    'data',
    'reference_type',
    'reference_id',
    'recorded_by',
])]
#[Table(name: 'asset_events')]
class AssetEvent extends Model
{
    use HasWorkflows;

    protected function casts(): array
    {
        return [
            'asset_id' => 'integer',
            'category' => AssetEventCategory::class,
            'occurred_at' => 'datetime',
            'value' => 'decimal:6',
            'data' => 'array',
            'reference_id' => 'integer',
            'recorded_by' => 'integer',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(config('asset.models.asset', Asset::class));
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    #[Scope]
    protected function category(Builder $query, AssetEventCategory|string $category): Builder
    {
        $value = $category instanceof AssetEventCategory ? $category->value : $category;

        return $query->where('category', $value);
    }

    #[Scope]
    protected function lifecycle(Builder $query): Builder
    {
        return $query->category(AssetEventCategory::Lifecycle);
    }

    #[Scope]
    protected function maintenance(Builder $query): Builder
    {
        return $query->category(AssetEventCategory::Maintenance);
    }

    #[Scope]
    protected function reliability(Builder $query): Builder
    {
        return $query->category(AssetEventCategory::Reliability);
    }

    #[Scope]
    protected function metering(Builder $query): Builder
    {
        return $query->category(AssetEventCategory::Metering);
    }
}
