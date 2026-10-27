<?php

declare(strict_types=1);

namespace Rimba\Asset\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Packstub\Flow\Concerns\HasWorkflows;
use Rimba\Asset\Enums\AssetStatus;
use Rimba\Attributing\Traits\HasThingAttributes;
use Rimba\Floorplan\Models\Concerns\HasLocationAssignments;

#[Fillable([
    'parent_id',
    'asset_type_id',
    'code',
    'name',
    'description',
    'status',
    'commissioned_at',
    'decommissioned_at',
])]
#[Table(name: 'assets')]
class Asset extends Model
{
    use HasLocationAssignments;
    use HasThingAttributes;
    use HasWorkflows;

    protected function casts(): array
    {
        return [
            'parent_id' => 'integer',
            'asset_type_id' => 'integer',
            'status' => AssetStatus::class,
            'commissioned_at' => 'datetime',
            'decommissioned_at' => 'datetime',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(
            config('asset.models.asset_type', AssetType::class),
            'asset_type_id',
        );
    }

    public function events(): HasMany
    {
        return $this->hasMany(
            config('asset.models.asset_event', AssetEvent::class)
        );
    }

    public function latestEvent(): HasOne
    {
        return $this->hasOne(
            config('asset.models.asset_event', AssetEvent::class)
        )->ofMany(['occurred_at' => 'max', 'id' => 'max']);
    }

    public function latestMeterReading(string $meter): ?AssetEvent
    {
        return $this->events()
            ->metering()
            ->where('type', $meter)
            ->latest('occurred_at')
            ->latest('id')
            ->first();
    }
}
