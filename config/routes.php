<?php

use App\Handler\ApiHandler;
use App\Handler\HomeHandler;
use Borsch\Application\App;

/**
 * @param App $app
 * @see https://github.com/nikic/FastRoute
 */
return function (App $app): void {
    $app->get('/', HomeHandler::class, 'home');
    $app->get('/api', ApiHandler::class, 'api');
};
