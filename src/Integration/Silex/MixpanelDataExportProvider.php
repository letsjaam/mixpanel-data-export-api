<?php

namespace Jaam\Mixpanel\Integration\Silex;

use Jaam\Mixpanel\DataExportApi;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class MixpanelDataExportProvider implements ServiceProviderInterface {

    public function register(Container $pimple) {
        $pimple['mixpanel.api'] = function($pimple) {
            return new DataExportApi($pimple['mixpanel.api_secret']);
        };
    }

}