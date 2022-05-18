<?php

namespace Itcomllc\Gmo\Providers;

use Illuminate\Support\ServiceProvider;

class GmoServiceProvider extends ServiceProvider
{
    /**
     * Initial startup process for all application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

        $config_path = dirname(__DIR__, 2) . DS . 'config' . DS . 'gmo.php';
        $this->publishes([
            $config_path => config_path('gmo.php'),
        ], 'gmo-config');

        $this->mergeConfigFrom($config_path, 'gmo');
    }
}
