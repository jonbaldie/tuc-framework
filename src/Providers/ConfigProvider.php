<?php

namespace Tuc\Providers;

use Tuc\Base\App;
use Tuc\Base\Config;

class ConfigProvider implements Provider
{
    /**
     * @param App $app
     *
     * @return void
     */
    public function boot(App $app): void
    {
        $config = $app->make(Config::class);
        $config_files = glob($app->getContext() . '/configs/*.php');

        foreach ($config_files as $config_file) {
            $array = require $config_file;
            $config->register(basename($config_file), $array);
        }
    }
}
