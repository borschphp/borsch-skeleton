<?php

use App\Handler\UserHandler;
use App\Listener\MonologListener;
use App\Middleware\ErrorHandlerMiddleware;
use App\Repository\InMemoryUserRepository;
use App\Repository\UserRepositoryInterface;
use Borsch\Application\App;
use Borsch\Application\ApplicationInterface;
use Borsch\Container\Container;
use Borsch\RequestHandler\RequestHandler;
use Borsch\Router\FastRouteRouter;
use Borsch\Router\RouterInterface;
use Laminas\Diactoros\ServerRequestFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

$container = new Container();

/*
 * Application definitions
 * -----------------------
 *
 * Main Borsch App definitions.
 * Here you can inject the basic dependency like the App instance, Router instance,
 * PSR-* instances, etc.
 */
$container->set(ApplicationInterface::class, App::class);
$container->set(RouterInterface::class, function () {
    $router = new FastRouteRouter();
    if (env('APP_ENV') == 'production') {
        $router->setCacheFile(__DIR__.'/../../storage/smarty/routes.cache.php');
    }

    return $router;
})->cache(true);
$container->set(RequestHandlerInterface::class, RequestHandler::class);
$container->set(ServerRequestInterface::class, function () {
    return ServerRequestFactory::fromGlobals();
});

/*
 * Pipeline Middlewares definitions
 * --------------------------------
 *
 * Here we configure ErrorHandlerMiddleware with a Monolog listener so that our exceptions will be logged.
 *
 * ImplicitHeadMiddleware and RouteMiddleware have a constructor that requires dependency.
 * When you add a new MiddlewareInterface in the pipeline, and it requires dependencies in the constructor,
 * then add the necessary definitions below.
 */
$container->set(ErrorHandlerMiddleware::class, function () {
    $logger = new Logger(env('APP_NAME', 'Borsch'));
    $logger->pushHandler(new StreamHandler(sprintf(
        '%s/../storage/logs/%s.log',
        __DIR__,
        env('LOG_CHANNEL', 'app')
    )));

    $error_handler = new ErrorHandlerMiddleware();
    $error_handler->addListener(new MonologListener($logger));

    return $error_handler;
});

/*
 * Routes Handlers definitions
 * ---------------------------
 *
 * As for pipeline middlewares, your routes handlers that have dependency must be listed here.
 * Our HomeHandler handler uses an instance of TemplateRendererInterface to display an HTML page, so it is listed below.
 */
$container->set(UserHandler::class);

/*
 * Match the UserRepositoryInterface with InMemoryUserRepository so that it can be used in UserHandler upper.
 */
$container
    ->set(UserRepositoryInterface::class, InMemoryUserRepository::class)
    ->addParameter(null)
    ->cache(true);

return $container;
