<?php

use App\{Handler\PeoplesHandler,
    Listener\MonologListener,
    Middleware\ErrorHandlerMiddleware,
    Repository\PeopleRepositoryInterface,
    Repository\SQLitePeopleRepository,
    Template\BasicTemplateEngine,
    Template\LatteEngine};
use Borsch\{Application\App,
    Application\ApplicationInterface,
    Container\Container,
    RequestHandler\ApplicationRequestHandlerInterface,
    RequestHandler\RequestHandler,
    Router\FastRouteRouter,
    Router\RouterInterface,
    Template\TemplateRendererInterface};
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
    if (isProduction()) {
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

$container
    ->set(
        Logger::class,
        fn() => (new Logger(env('APP_NAME', 'App')))
            ->pushHandler(new StreamHandler(logs_path(env('LOG_CHANNEL', 'app').'.log')))
    )
    -> cache(true);

/*
 * Pipeline Middlewares definitions
 * --------------------------------
 * Here we configure ErrorHandlerMiddleware with a Monolog listener so that our exceptions will be logged.
 */

$container
    ->set(ErrorHandlerMiddleware::class)
    ->addMethod('addListener', [$container->get(MonologListener::class)]);

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
    // For MySQL with connexion information from .env file
    //$pdo = new PDO(
    //    'mysql:host='.env('DB_HOST').';port='.env('DB_PORT').';dbname='.env('DB_DATABASE').';charset=utf8mb4',
    //    env('DB_USERNAME'),
    //    env('DB_PASSWORD')
    //);

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

$container
    ->set(TemplateRendererInterface::class, LatteEngine::class)
    ->cache(true);

return $container;
