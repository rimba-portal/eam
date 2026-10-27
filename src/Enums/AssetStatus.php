<?php

declare(strict_types=1);

namespace Rimba\Asset\Enums;

enum AssetStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Idle = 'idle';
    case OutOfService = 'out_of_service';
    case Decommissioned = 'decommissioned';
    case Disposed = 'disposed';
}
