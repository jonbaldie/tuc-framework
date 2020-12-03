<?php

namespace Tuc\Providers;

use Tuc\Base\App;

interface Provider
{
    /**
     * @param App $app
     *
     * @return void
     */
    public function boot(App $app): void;
}
