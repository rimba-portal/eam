<?php

declare(strict_types=1);

namespace Rimba\Asset;

use Rimba\Asset\Models\AssetEvent;
use Rimba\Asset\Observers\AssetEventObserver;
use Rimba\Base\Services\BitesServiceProvider;

final class AssetServiceProvider extends BitesServiceProvider
{
    protected function registerPackage(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/asset.php', 'asset');
    }

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        AssetEvent::observe(AssetEventObserver::class);

        $this->publishes([
            __DIR__.'/../config/asset.php' => config_path('asset.php'),
        ], 'asset-config');
    }
}
