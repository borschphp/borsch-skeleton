<?php

use App\{Formatter\HtmlFormatter,
    Handler\PeoplesHandler,
    Listener\MonologListener,
    Middleware\ErrorHandlerMiddleware,
    Repository\PeopleRepositoryInterface,
    Repository\SQLitePeopleRepository,
    Template\BasicTemplateEngine};
use Borsch\{Application\App,
    Application\ApplicationInterface,
    Container\Container,
    RequestHandler\ApplicationRequestHandlerInterface,
    RequestHandler\RequestHandler,
    Router\FastRouteRouter,
    Router\RouterInterface};
use Laminas\Diactoros\ServerRequestFactory;
use Monolog\{Handler\StreamHandler, Logger};
use Psr\Http\Message\ServerRequestInterface;

$container = new Container();

/*
 * Application definitions
 * -----------------------
 * Main Borsch App definitions (like the App instance, Router instance, PSR-* instances, etc).
 */

$container->set(ApplicationInterface::class, App::class);
$container->set(RouterInterface::class, function () {
    $router = new FastRouteRouter();
    if (env('APP_ENV') == 'production') {
        $router->setCacheFile(cache_path('routes.cache.php'));
    }

    return $router;
})->cache(true);
$container->set(ApplicationRequestHandlerInterface::class, RequestHandler::class);
$container->set(ServerRequestInterface::class, fn() => ServerRequestFactory::fromGlobals());

/*
 * Log & Monitoring definitions
 * ----------------------------
 * A default Logger is defined, it can be used in our middlewares, handlers or any other stuff as well.
 */

$container->set(Logger::class, function () {
    $name = env('APP_NAME', 'App');
    $logger = new Logger($name);
    $logger->pushHandler(new StreamHandler(logs_path(sprintf(
        '%s.log',
        env('LOG_CHANNEL', 'app')
    ))));

    return $logger;
})-> cache(true);

/*
 * Pipeline Middlewares definitions
 * --------------------------------
 * Here we configure ErrorHandlerMiddleware with a Monolog listener so that our exceptions will be logged.
 */

$container
    ->set(ErrorHandlerMiddleware::class)
    ->addMethod('addListener', [$container->get(MonologListener::class)])
    ->addMethod('setFormatter', [$container->get(HtmlFormatter::class)]);

/*
 * Routes Handlers definitions
 * ---------------------------
 * As for pipeline middlewares, your routes handlers that have dependency must be listed here.
 */

$container->set(PeoplesHandler::class);

/*
 * Database
 * --------
 * This skeleton implements a simple CRUD API that stores and updates a SQLite database via PDO.
 */

$container->set(PDO::class, function () {
    $pdo = new PDO('sqlite:'.storage_path('database.sqlite'));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    return $pdo;
})->cache(true);

/*
 * Match the UserRepositoryInterface with InMemoryUserRepository so that it can be used in UserHandler upper.
 */

$container
    ->set(PeopleRepositoryInterface::class, SQLitePeopleRepository::class)
    ->cache(true);

/*
 * Template engine
 */

$container->set(
    BasicTemplateEngine::class,
    fn() => (new BasicTemplateEngine())
        ->setTemplateDir(storage_path('views'))
        ->setCacheDir(cache_path('views'))
        ->useCache(env('APP_ENV') == 'production')
)->cache(true);

return $container;
