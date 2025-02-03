<?php

use App\Handler\{AlbumHandler, HealthCheckHandler};
use Borsch\Application\Application;
use Borsch\Application\Server\HttpMethods;

/**
 * @param Application $app
 * @see https://github.com/nikic/FastRoute
 */
return static function(Application $app): void {
    $app->group('/api', function (Application $app) {
        $app->any('/albums[/{id:\d+}]', AlbumHandler::class, 'albums');

        // Health checks
        $app->get('/healthcheck', HealthCheckHandler::class, 'healthcheck');
    });
};
