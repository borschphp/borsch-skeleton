<?php

use App\Handler\{AlbumHandler, ArtistHandler, HealthCheckHandler, OpenApiHandler};
use Borsch\Application\Application;

/**
 * @param Application $app
 * @see https://github.com/nikic/FastRoute
 */
return static function(Application $app): void {
    $app->group('/api', function (Application $app) {
        if (!isProduction()) {
            $app->get('/openapi[.{format:json|yml|yaml}]', OpenApiHandler::class, 'openapi');
        }

        $app->any('/albums[/{id:\d+}]', AlbumHandler::class, 'albums');
        $app->any('/artists[/{id:\d+}]', ArtistHandler::class, 'artists');

        // Health checks
        $app->get('/healthcheck', HealthCheckHandler::class, 'healthcheck');
    });
};
