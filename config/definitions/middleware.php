<?php

use App\Handler\ApiHandler;
use App\Handler\HomeHandler;
use App\Middleware\ImplicitHeadMiddleware;
use App\Middleware\RouteMiddleware;

/*
 * Middleware definitions, used in the pipeline or routes.
 */
return [
    // Pipeline
    // For now, only ImplicitHeadMiddleware and RouteMiddleware have a constructor that requires dependency.
    // When you add a new MiddlewareInterface in the pipeline, and it requires dependencies in the constructor,
    // then add the definition below.
    ImplicitHeadMiddleware::class => DI\autowire(),
    RouteMiddleware::class => DI\autowire(),

    // Routes
    // See Pipeline upper for more details.
    HomeHandler::class => DI\autowire(),
    ApiHandler::class => DI\autowire(),
];
