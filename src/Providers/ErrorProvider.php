<?php

namespace Tuc\Providers;

use Tuc\Base\App;
use Whoops\Handler\Handler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use function php_sapi_name;

class ErrorProvider implements Provider
{
    /**
     * @param App $app
     *
     * @return void
     */
    public function boot(App $app): void
    {
        if (class_exists(Run::class)) {
            $whoops = new Run;
            $whoops->pushHandler($this->handler());
            $whoops->register();
        }
    }

    /**
     * @return Handler
     */
    protected function handler(): Handler
    {
        if (php_sapi_name() === 'cli') {
            return new PlainTextHandler;
        }

        return new PrettyPageHandler;
    }
}
