<?php

use Borsch\Application\App;
use Borsch\Application\ApplicationInterface;
use Borsch\RequestHandler\RequestHandler;
use Borsch\Router\FastRouteRouter;
use Borsch\Router\RouterInterface;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/*
 * Main Borsch App definitions definitions.
 * Here you can inject the basic dependency like the App, Router, PSR-* instance.
 */
return [
    ApplicationInterface::class => DI\autowire(App::class),
    RouterInterface::class => DI\autowire(FastRouteRouter::class)/*->method(
        'setCacheFile',
        __DIR__.'/../../storage/cache/routes'
    )*/,
    RequestHandlerInterface::class => DI\autowire(RequestHandler::class),
    ServerRequestInterface::class => DI\factory(function () {
        return ServerRequestFactory::fromGlobals();
    })
];
