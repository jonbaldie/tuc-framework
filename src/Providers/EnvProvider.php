<?php

namespace Tuc\Providers;

use Symfony\Component\Dotenv\Dotenv;
use Tuc\Base\App;

class EnvProvider implements Provider
{
    /**
     * @param App $app
     * @return void
     */
    public function boot(App $app): void
    {
        $dotenv = new Dotenv;
        $dotenv->load($app->getContext() . '/.env');
    }
}
