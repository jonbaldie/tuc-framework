<?php

namespace Tuc\Providers;

use Slim\Views\PhpRenderer;
use Tuc\Base\App;

class ViewProvider implements Provider
{
    /**
     * @param App $app
     * @return void
     */
    public function boot(App $app): void
    {
        $app->bind('view', new PhpRenderer($app->config('views.location')));
    }
}
