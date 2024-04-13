<?php

use App\Handler\{HealthCheckHandler, HomeHandler, PeoplesHandler};
use Borsch\Application\App;

/**
 * @param App $app
 * @see https://github.com/nikic/FastRoute
 */
return static function(App $app): void {
    $app->get('/', HomeHandler::class, 'home');

    $app->match(
        ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
        '/api/peoples[/{id:\d+}]',
        PeoplesHandler::class,
        'peoples'
    );

    // Health checks
    $app->get('/healthcheck', HealthCheckHandler::class, 'healthcheck');
};
