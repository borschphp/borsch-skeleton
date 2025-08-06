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

    $container->set(RequestHandlerRunnerInterface::class, static function (ContainerInterface $container) {
        return new RequestHandlerRunner(
            $container->get(RequestHandlerInterface::class),
            new Emitter(),
            static fn() => $container->get(ServerRequestInterface::class),
            static function() use ($container) {
                $engine = $container->get(TemplateRendererInterface::class);
                $response = ($container->get(ResponseFactoryInterface::class))->createResponse(500);

                $response->getBody()->write($engine->render('500.tpl'));

                return $response;
            }
        );
    });

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
