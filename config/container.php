<?php

use Borsch\Application\Factory\HandlerFactory;
use Borsch\Application\Server\PipeMiddleware;
use Borsch\Middleware\BodyParserMiddleware;
use Borsch\Middleware\ContentLengthMiddleware;
use Borsch\Middleware\DispatchMiddleware;
use Borsch\Middleware\ErrorHandlerMiddleware;
use Borsch\Middleware\ImplicitHeadMiddleware;
use Borsch\Middleware\ImplicitOptionsMiddleware;
use Borsch\Middleware\MethodNotAllowedMiddleware;
use Borsch\Middleware\NotFoundHandlerMiddleware;
use Borsch\Middleware\RouteMiddleware;
use Borsch\Middleware\TrailingSlashMiddleware;
use Borsch\Middleware\UploadedFilesParserMiddleware;
use Borsch\RequestHandler\Emitter;
use Borsch\RequestHandler\EmitterInterface;
use Borsch\RequestHandler\RequestHandler;
use Borsch\RequestHandler\RequestHandlerInterface;
use Borsch\RequestHandler\RequestHandlerRunner;
use Borsch\RequestHandler\RequestHandlerRunnerInterface;
use League\Container\{Container, ReflectionContainer};
use ProblemDetails\ProblemDetailsMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

$container = new Container();

$container->defaultToShared();
$container->delegate(new ReflectionContainer(true));

$container->add(RequestHandlerInterface::class, function (ContainerInterface $container) {
    $handler_factory = new HandlerFactory($container);

    $handler = new RequestHandler();
    $handler->middleware($container->get(ErrorHandlerMiddleware::class));
    $handler->middleware($container->get(ProblemDetailsMiddleware::class));
    $handler->middleware($container->get(TrailingSlashMiddleware::class));
    $handler->middleware($container->get(ContentLengthMiddleware::class));
    $handler->middleware($container->get(RouteMiddleware::class));
    $handler->middleware($container->get(ImplicitHeadMiddleware::class));
    $handler->middleware($container->get(ImplicitOptionsMiddleware::class));
    $handler->middleware($container->get(MethodNotAllowedMiddleware::class));
    $handler->middleware(new PipeMiddleware('/api', BodyParserMiddleware::class, $handler_factory));
    $handler->middleware(new PipeMiddleware('/api', UploadedFilesParserMiddleware::class, $handler_factory));
    $handler->middleware($container->get(DispatchMiddleware::class));
    $handler->middleware($container->get(NotFoundHandlerMiddleware::class));

    return $handler;
})->addArgument($container);

$container->add(EmitterInterface::class, Emitter::class);

$container->add(RequestHandlerRunnerInterface::class, function (ContainerInterface $container) {
    return new RequestHandlerRunner(
        $container->get(RequestHandlerInterface::class),
        $container->get(EmitterInterface::class),
        static fn() => $container->get(ServerRequestInterface::class),
        static function(Throwable $e) use ($container) {
            $response = ($container->get(ResponseFactoryInterface::class))->createResponse(500);
            $response->getBody()->write(sprintf(
                'An error occurred: %s',
                $e->getMessage()
            ));
            return $response;
        }
    );
})->addArgument($container);

(require_once __DIR__.'/containers/app.container.php')($container);
(require_once __DIR__.'/containers/logs.container.php')($container);
(require_once __DIR__.'/containers/pipeline.container.php')($container);
(require_once __DIR__.'/containers/template.container.php')($container);
(require_once __DIR__.'/containers/database.container.php')($container);

return $container;
