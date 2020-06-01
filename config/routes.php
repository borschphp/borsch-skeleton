<?php

use App\Handler\ApiHandler;
use App\Handler\HomeHandler;
use Borsch\Application\App;
use Borsch\Container\Container;

/**
 * @param App $app
 * @param Container $container
 * @see https://github.com/nikic/FastRoute
 */
return function (App $app, Container $container): void {
    $app->get('/', HomeHandler::class, 'home');
    $app->get('/api', ApiHandler::class, 'api');
};
