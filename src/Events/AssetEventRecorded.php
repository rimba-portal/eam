<?php

declare(strict_types=1);

namespace Rimba\Asset\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Rimba\Asset\Models\AssetEvent;

final class AssetEventRecorded
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly AssetEvent $assetEvent) {}
}
