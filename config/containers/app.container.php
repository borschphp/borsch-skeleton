<?php

use Borsch\{Application\Application,
    Application\ApplicationInterface,
    RequestHandler\RequestHandler,
    RequestHandler\RequestHandlerInterface,
    Router\FastRouteRouter,
    Router\RouterInterface};
use Laminas\Diactoros\ServerRequestFactory;
use League\Container\Container;
use Psr\Http\Message\ServerRequestInterface;

return static function(Container $container): void {
    $container
        ->add(ApplicationInterface::class, Application::class)
        ->addArgument(RequestHandlerInterface::class)
        ->addArgument(RouterInterface::class)
        ->addArgument($container);

    $container->add(RouterInterface::class, function () {
        $router = new FastRouteRouter();
        if (isProduction()) {
            $router->setCacheFile(cache_path('routes.cache.php'));
        }

        return $router;
    });

    $container->add(RequestHandlerInterface::class, RequestHandler::class);

    $container->add(ServerRequestInterface::class, fn() => ServerRequestFactory::fromGlobals())->setShared(false);
};
