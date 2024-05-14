<?php

use App\Handler\{HealthCheckHandler, PeoplesHandler};
use Borsch\Application\App;

/**
 * @param App $app
 * @see https://github.com/nikic/FastRoute
 */
return static function(App $app): void {
    $app->group('/api', function (App $app) {
        $app->match(
            ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
            '/peoples[/{id:\d+}]',
            PeoplesHandler::class,
            'peoples'
        );

        // Health checks
        $app->get('/healthcheck', HealthCheckHandler::class, 'healthcheck');
    });
};
