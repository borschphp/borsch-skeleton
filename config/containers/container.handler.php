<?php

use Borsch\Container\Container;
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
use Borsch\RequestHandler\{Emitter, RequestHandler, RequestHandlerRunner, RequestHandlerRunnerInterface};
use Borsch\Template\TemplateRendererInterface;
use ProblemDetails\ProblemDetailsMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\{ResponseFactoryInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

return static function (Container $container) {

    /*
     * The RequestHandlerRunner is responsible for running the RequestHandler and emit a response.
     *
     * As parameters, it takes:
     * - A RequestHandlerInterface instance that will handle the request
     * - An Emitter instance that will emit the response
     * - A callable that returns the ServerRequestInterface instance
     * - A callable that returns a fallback response in case of an error
     */
    $container->set(
        RequestHandlerRunnerInterface::class,
        static function (
            RequestHandlerInterface $handler,
            ServerRequestInterface $request,
            TemplateRendererInterface $renderer,
            ResponseFactoryInterface $factory
        ) {
            return new RequestHandlerRunner(
                $handler,
                new Emitter(),
                static fn() => $request,
                static function() use ($renderer, $factory) {
                    $response = $factory->createResponse(500);
                    $response->getBody()->write($renderer->render('500.tpl'));

                    return $response;
                }
            );
    });

    /*
     * The RequestHandler is responsible for handling the request and returning a response.
     *
     * It is composed of several middlewares that will be executed in the order they are added (FIFO).
     * Predefined middlewares are included to handle common tasks, feel free to add your own.
     */
    $container->set(RequestHandlerInterface::class, static function (ContainerInterface $container) {
        return (new RequestHandler())
            ->middleware($container->get(ErrorHandlerMiddleware::class))
            ->middleware($container->get(ProblemDetailsMiddleware::class))
            ->middleware($container->get(TrailingSlashMiddleware::class))
            ->middleware($container->get(ContentLengthMiddleware::class))
            ->middleware($container->get(RouteMiddleware::class))
            ->middleware($container->get(ImplicitHeadMiddleware::class))
            ->middleware($container->get(ImplicitOptionsMiddleware::class))
            ->middleware($container->get(MethodNotAllowedMiddleware::class))
            ->middleware($container->get(BodyParserMiddleware::class))
            ->middleware($container->get(UploadedFilesParserMiddleware::class))
            ->middleware($container->get(DispatchMiddleware::class))
            ->middleware($container->get(NotFoundHandlerMiddleware::class));
    });

};
