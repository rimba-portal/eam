<?php

declare(strict_types=1);

namespace Rimba\Asset\Observers;

use LogicException;

final class AssetEventObserver
{
    public function updating(): void
    {
        if (config('asset.events.immutable', true)) {
            throw new LogicException('Asset events are immutable. Record a correcting event instead.');
        }
    }

    public function deleting(): void
    {
        if (config('asset.events.immutable', true)) {
            throw new LogicException('Asset events are immutable and cannot be deleted.');
        }
    }
}
