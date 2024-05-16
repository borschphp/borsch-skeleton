<?php

use Borsch\{Application\App,
    Application\ApplicationInterface,
    Container\Container,
    RequestHandler\ApplicationRequestHandlerInterface,
    RequestHandler\RequestHandler,
    Router\FastRouteRouter,
    Router\RouterInterface};
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

return static function(Container $container): void {
    $container->set(ApplicationInterface::class, App::class);
    $container->set(RouterInterface::class, function () {
        $router = new FastRouteRouter();
        if (isProduction()) {
            $router->setCacheFile(cache_path('routes.cache.php'));
        }

        return $router;
    })->cache(true);
    $container->set(ApplicationRequestHandlerInterface::class, RequestHandler::class);
    $container->set(ServerRequestInterface::class, fn() => ServerRequestFactory::fromGlobals());
};
