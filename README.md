# rimba/asset

Lean enterprise asset management kernel for Rimba.

## Domain boundary

| Area | Owner |
|---|---|
| Asset identity and hierarchy | `rimba/asset` |
| Dynamic technical/current properties | `rimba/sifat` through `HasThingAttributes` |
| Physical location and movement | `rimba/pelan` through `HasLocationAssignments` |
| Historical lifecycle, maintenance, reliability, and meter records | `AssetEvent` |
| Visual automation | `packstub/filament-flow` |

## Models

| Model | Workflow-enabled | Purpose |
|---|---:|---|
| `Asset` | Yes | A maintainable asset with hierarchy, status, attributes, locations, and events. |
| `AssetType` | No | Stable hierarchical classification and configuration data. |
| `AssetEvent` | Yes | Append-only asset history and automation trigger stream. |

`AssetType` intentionally has no workflow trait. It is reference/configuration data. `AssetEvent` is workflow-enabled so a newly recorded failure, service, threshold reading, commissioning, or disposal event can start an automation.

## Installation

```bash
composer require rimba/asset
php artisan migrate
```

Register Filament Flow in the intended panel:

```php
use Packstub\Flow\FlowPlugin;

$panel->plugin(
    FlowPlugin::make()->navigationGroup('Automation'),
);
```

Run the Flow scheduler and queue worker:

```cron
* * * * * php /path/to/artisan packstub-flow:cron
```

```bash
php artisan queue:work
```

## Recording events

```php
use Rimba\Asset\Services\AssetEventService;

$events = app(AssetEventService::class);

$events->lifecycle($asset, 'commissioned');

$events->maintenance($asset, 'serviced', [
    'description' => '2,000 hour preventive service',
    'data' => ['result' => 'passed'],
]);

$events->reliability($asset, 'failed', [
    'description' => 'Spindle bearing seizure',
    'data' => [
        'failure_mode' => 'bearing_seizure',
        'cause' => 'insufficient_lubrication',
        'severity' => 'major',
        'downtime_minutes' => 185,
    ],
]);

$events->meter($asset, 'operating_hours', 12542.7, 'hour');
```

## Workflow examples

1. `Asset` updated: when `status` changes to `decommissioned`, request approval and notify the custodian.
2. `AssetEvent` created: when `category = reliability` and `type = failed`, notify maintenance and create an external work request.
3. `AssetEvent` created: when `category = metering`, `type = operating_hours`, and `value >= 2000`, start the service workflow.
4. `AssetEvent` created: when `category = lifecycle` and `type = commissioned`, notify the accountable team.

## Important rule

`AssetEvent` is immutable. Never edit history. Record a correcting event and relate it through `reference_type` and `reference_id` when necessary.
