<?php

declare(strict_types=1);

namespace Rimba\Asset\Enums;

enum AssetEventCategory: string
{
    case Lifecycle = 'lifecycle';
    case Maintenance = 'maintenance';
    case Reliability = 'reliability';
    case Metering = 'metering';
}
