<?php

namespace Tuc\Providers;

use Tuc\Base\App;

class RoutingProvider implements Provider
{
    /**
     * @param App $app
     *
     * @return void
     */
    public function boot(App $app): void
    {
        $app->addRoutingMiddleware();
    }
}
