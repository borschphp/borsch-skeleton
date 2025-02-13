<?php

use App\Handler\{AlbumHandler, ArtistHandler, HealthCheckHandler, OpenApiHandler, RedocHandler};
use Borsch\Application\Application;

/**
 * @param Application $app
 * @see https://github.com/nikic/FastRoute
 */
return static function(Application $app): void {
    $app->group('/api', function (Application $app) {
        if (!isProduction()) {
            // Redoc (/swagger also available)
            $app->get('/openapi[.{format:json|yml|yaml}]', OpenApiHandler::class, 'openapi');
            $app->get('/{redoc:redoc|swagger}', RedocHandler::class);
        }

        $app->any('/albums[/{id:\d+}]', AlbumHandler::class, 'albums');
        $app->any('/artists[/{id:\d+}]', ArtistHandler::class, 'artists');

        // Health checks
        $app->get('/healthcheck', HealthCheckHandler::class, 'healthcheck');
    });
};
