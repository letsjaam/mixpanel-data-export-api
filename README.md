# Mixpanel Data Export API
This library wraps the [Mixpanel Data Export API](https://mixpanel.com/help/reference/data-export-api) for PHP.

## Installation
```bash
composer require jaam/mixpanel-data-export-api
```

## Usage
The `DataApiExport` class includes two public methods - `data` and `export` - one for each of the Data Export APIs.

Full documentation of every endpoint, their parameters and responses can be found in the [Mixpanel Data Export API documentation](https://mixpanel.com/help/reference/data-export-api).

#### Setup
```php
<?php

require_once 'vendor/autoload.php';

use Jaam\Mixpanel\DataExportApi;
use Jaam\Mixpanel\DataExportApiException;

$mixpanel = new DataExportApi('YOUR SECRET'); // Secret located in Mixpanel project settings
```

#### Data
See [Data Export API documentation](https://mixpanel.com/help/reference/data-export-api) for methods, parameters and response examples.

```php
// Perform setup, as above
try {
    // Retrieve events from `events` endpoint
    $data = $mixpanel->data('events', [
        'events' => ['event_name'], // Array of event names
        'type' => 'unique',
        'unit' => 'day',
        'from_date' => '2016-12-01',
        'to_date' => '2016-12-31'
    ]);

    // $data is an array
} catch ( DataExportApiException $e )
    // Handle exception
}
```

#### Export
See [Exporting Raw Data documentation](https://mixpanel.com/help/reference/exporting-raw-data) for parameters and response examples.

```php
// Perform setup, as above
try {
    // Export raw data
    $data = $mixpanel->export([
        'from_date' => '2016-12-01',
        'to_date' => '2016-12-31'
    ]);

    // $data is an array
} catch ( DataExportApiException $e )
    // Handle exception
}
```

## Silex Integration
A small integration with [Silex](http://silex.sensiolabs.org/) is provided via `Jaam\Mixpanel\Integration\Silex\MixpanelDataExportProvider`.

```php
// Bootstrap Silex app

use Jaam\Mixpanel\Integration\Silex\MixpanelDataExportProvider;

$app['mixpanel.api_secret'] = 'YOUR SECRET'; // Secret located in Mixpanel project settings
$app->register(new MixpanelDataExportProvider);

// Use via `mixpanel.api` server later in application
$data = $app['mixpanel.api']->export([
    'from_date' => '2016-12-14',
    'to_date' => '2016-12-18'
]);
```