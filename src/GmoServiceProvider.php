<?php

namespace GMO;

use Illuminate\Support\ServiceProvider;

class GmoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

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

        $config_path = dirname(__DIR__, 1) . DS . 'config' . DS . 'gmo.php';
        $this->publishes([
            $config_path => config_path('gmo.php'),
        ], 'gmo-config');

        $this->mergeConfigFrom($config_path, 'gmo');
    }
}
