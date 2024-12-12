<?php

use App\Handler\{HealthCheckHandler, PeoplesHandler};
use Borsch\Application\Application;

/**
 * @param Application $app
 * @see https://github.com/nikic/FastRoute
 */
return static function(Application $app): void {
    $app->group('/api', function (Application $app) {
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
