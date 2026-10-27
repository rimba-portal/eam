<?php

declare(strict_types=1);

use Rimba\Asset\Models\Asset;
use Rimba\Asset\Models\AssetEvent;
use Rimba\Asset\Models\AssetType;

return [
    'models' => [
        'asset' => Asset::class,
        'asset_type' => AssetType::class,
        'asset_event' => AssetEvent::class,
    ],

    'workflows' => [
        'asset' => true,
        'asset_event' => true,
    ],

    'events' => [
        'immutable' => true,
    ],
];
