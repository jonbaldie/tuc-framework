<?php

namespace Tuc\Providers;

use Tuc\Base\App;

class ErrorProvider implements Provider
{
    /**
     * @param App $app
     *
     * @return void
     */
    public function boot(App $app): void
    {
        if (class_exists(\Whoops\Run::class)) {
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();
        }
    }
}
