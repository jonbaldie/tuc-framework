<?php

namespace Tuc\Providers;

use Tuc\Base\App;

class DataProvider implements Provider
{
    /**
     * @param App $app
     *
     * @return void
     */
    public function boot(App $app): void
    {
        // @todo Register Doctrine DBAL
    }
}
