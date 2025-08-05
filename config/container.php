<?php

use Borsch\Container\Container;
use Borsch\Http\Response\{HtmlResponse, JsonResponse};
use Borsch\Http\Factory\{ResponseFactory, ServerRequestFactory, StreamFactory, UploadedFileFactory};
use Borsch\Latte\LatteRenderer;
use Borsch\Router\Contract\{RouterInterface, RouteInterface};
use Borsch\Router\FastRouteRouter;
use Borsch\Router\Loader\AttributeRouteLoader;
use Borsch\Template\TemplateRendererInterface;
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
use Borsch\RequestHandler\{Emitter,
    RequestHandler,
    RequestHandlerInterface,
    RequestHandlerRunner,
    RequestHandlerRunnerInterface};
use Laminas\Db\Adapter\{Adapter, AdapterInterface};
use Monolog\{Handler\StreamHandler, Level, Logger, Processor\PsrLogMessageProcessor};
use ProblemDetails\{ProblemDetails, ProblemDetailsException, ProblemDetailsMiddleware};
use Psr\Container\ContainerInterface;
use Psr\Http\Message\{ResponseFactoryInterface,
    ResponseInterface,
    ServerRequestInterface,
    StreamFactoryInterface,
    UploadedFileFactoryInterface};

$container = new Container();
$container->setCacheByDefault(true);

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

$container->set(ErrorHandlerMiddleware::class, static fn(TemplateRendererInterface $renderer) => new ErrorHandlerMiddleware(
    static function (Throwable $throwable, ServerRequestInterface $request) use ($renderer): ResponseInterface {
        if (str_starts_with($request->getUri()->getPath(), '/api')) {
            return new JsonResponse(new ProblemDetails(
                type: '://problem/internal-server-error',
                title: 'Internal server error.',
                status: 500,
                detail: $throwable->getMessage()
            ), 500);
        }

        return new HtmlResponse(
            $renderer->render('500.tpl'),
            500
        );
    }
));

$container->set(NotFoundHandlerMiddleware::class, static function (TemplateRendererInterface $renderer) {
    return new NotFoundHandlerMiddleware(static function (ServerRequestInterface $request) use ($renderer): ResponseInterface {
        if (str_starts_with($request->getUri()->getPath(), '/api')) {
            throw new ProblemDetailsException(new ProblemDetails(
                type: '://problem/not-found',
                title: 'Not found.',
                status: 404,
                detail: "The requested uri ({$request->getUri()->getPath()}) could not be found."
            ));
        }

        return new HtmlResponse(
            $renderer->render('404.tpl'),
            404
        );
    });
});

$container->set(ServerRequestInterface::class, static function () {
    return (new ServerRequestFactory())->createServerRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_SERVER);
})->cache(false);

$container->set(UploadedFileFactoryInterface::class, UploadedFileFactory::class);
$container->set(StreamFactoryInterface::class, StreamFactory::class);
$container->set(ResponseFactoryInterface::class, ResponseFactory::class);

$container->set(
    AttributeRouteLoader::class,
    static fn(ContainerInterface $container) => (
        new AttributeRouteLoader(
            [__ROOT_DIR__ . '/src/Application'],
            $container,
            cache_path('loader.routes.cache.php'),
            !isProduction()
        ))->load()
);

$container->set(RouterInterface::class, static function (AttributeRouteLoader $loader) {
    $routes = $loader->getRoutes();

    if (isProduction()) {
        return new FastRouteRouter(
            array_combine(
                array_map(fn(RouteInterface $route) => $route->getName(), $routes),
                $routes
            ),
            cache_path('router.routes.cache.php')
        );
    }

    $router = new FastRouteRouter();
    foreach ($routes as $route) {
        $router->addRoute($route);
    }

    return $router;
});

$container->set(NotFoundHandlerMiddleware::class, static function (TemplateRendererInterface $renderer) {
    return new NotFoundHandlerMiddleware(static function (ServerRequestInterface $request) use ($renderer): ResponseInterface {
        if (str_starts_with($request->getUri()->getPath(), '/api')) {
            throw new ProblemDetailsException(new ProblemDetails(
                type: '://problem/not-found',
                title: 'Not found.',
                status: 404,
                detail: "The requested uri ({$request->getUri()->getPath()}) could not be found."
            ));
        }

        return new HtmlResponse(
            $renderer->render('404.tpl'),
            404
        );
    });
});

$container->set(TemplateRendererInterface::class, fn() => new LatteRenderer(storage_path('views'), cache_path('views'), !isProduction()));

$container->set(Logger::class, function (): Logger {
    $name = env('APP_NAME', 'App');

    $handlers = [
        new StreamHandler(
            logs_path(env('LOG_CHANNEL', 'app').'.log'),
            Level::fromName(env('LOG_LEVEL', 'Debug'))
        )
    ];

    $processors = [new PsrLogMessageProcessor(removeUsedContextFields: true)];
    $datetime_zone = new DateTimeZone(env('TIMEZONE', 'UTC'));

    return new Logger($name, $handlers, $processors, $datetime_zone);
});

$container
    ->set(AdapterInterface::class, Adapter::class)
    ->addParameter([
        'driver' => 'Pdo_Sqlite',
        'dsn' => 'sqlite:'.storage_path('database.sqlite')
    ]);

return $container;
