<?php

use Borsch\Middleware\{BodyParserMiddleware,
    ContentLengthMiddleware,
    DispatchMiddleware,
    ErrorHandlerMiddleware,
    ImplicitHeadMiddleware,
    ImplicitOptionsMiddleware,
    MethodNotAllowedMiddleware,
    NotFoundHandlerMiddleware,
    RouteMiddleware,
    TrailingSlashMiddleware,
    UploadedFilesParserMiddleware};
use Borsch\Application\Application;
use ProblemDetails\ProblemDetailsMiddleware;

/**
 * Set up your middleware pipeline.
 * It works as FIFO, place your middleware as necessary.
 *
 * @param Application $app
 */
return static function(Application $app): void {
    // This should be the first middleware to catch all Exceptions.
    $app->pipe(ErrorHandlerMiddleware::class);

    // This middleware will handle exceptions coming from API calls throwing a ProblemDetailsException.
    $app->pipe(ProblemDetailsMiddleware::class);

    // Pipe more middleware here that you want to execute on every request.
    $app->pipe(TrailingSlashMiddleware::class);
    $app->pipe(ContentLengthMiddleware::class);

    // Register the routing middleware in the pipeline.
    // It will add the Borsch\Router\RouteResult request attribute.
    $app->pipe(RouteMiddleware::class);

    // The following handle routing failures for common conditions:
    // - HEAD request but no routes answer that method
    // - OPTIONS request but no routes answer that method
    // - method not allowed
    // Order here matters, the MethodNotAllowedMiddleware should be placed after the Implicit*Middleware.
    $app->pipe(ImplicitHeadMiddleware::class);
    $app->pipe(ImplicitOptionsMiddleware::class);
    $app->pipe(MethodNotAllowedMiddleware::class);

    // Middleware can be attached to specific paths, allowing you to mix and match
    // applications under a common domain.
    $app->pipe('/api', [
        BodyParserMiddleware::class,
        UploadedFilesParserMiddleware::class
    ]);

    // This will take care of generating the response of your matched route.
    $app->pipe(DispatchMiddleware::class);

    // If no Response is returned by any middleware, then send a 404 Not Found response.
    // You can provide other fallback middleware to execute.
    $app->pipe(NotFoundHandlerMiddleware::class);
};
