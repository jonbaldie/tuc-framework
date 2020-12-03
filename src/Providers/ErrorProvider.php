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
        if ($app->config('app.mode') === 'production') {
            //
        } else {
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();
        }
    }
}
