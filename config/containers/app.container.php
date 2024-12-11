<?php

use Borsch\{Application\Application,
    Application\ApplicationInterface,
    Container\Container,
    RequestHandler\RequestHandler,
    RequestHandler\RequestHandlerInterface,
    Router\FastRouteRouter,
    Router\RouterInterface};
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

return static function(Container $container): void {
    $container->set(ApplicationInterface::class, Application::class);
    $container->set(RouterInterface::class, function () {
        $router = new FastRouteRouter();
        if (isProduction()) {
            $router->setCacheFile(cache_path('routes.cache.php'));
        }

        return $router;
    })->cache(true);
    $container->set(RequestHandlerInterface::class, RequestHandler::class);
    $container->set(ServerRequestInterface::class, fn() => ServerRequestFactory::fromGlobals());
};
