<?php

use App\Handler\HomeHandler;
use App\Listener\MonologListener;
use App\Middleware\ErrorHandlerMiddleware;
use App\Middleware\ImplicitHeadMiddleware;
use App\Middleware\RouteMiddleware;
use Borsch\Application\App;
use Borsch\Application\ApplicationInterface;
use Borsch\Container\Container;
use Borsch\RequestHandler\RequestHandler;
use Borsch\Router\FastRouteRouter;
use Borsch\Router\RouterInterface;
use Borsch\Smarty\Smarty;
use Borsch\Template\TemplateRendererInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
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
        $router->setCacheFile(__DIR__.'/../../storage/smarty/routes.smarty.php');
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
$container->set(ImplicitHeadMiddleware::class);
$container->set(RouteMiddleware::class);

/*
 * Routes Handlers definitions
 * ---------------------------
 *
 * As for pipeline middlewares, your routes handlers that have dependency must be listed here.
 * Our HomeHandler handler uses an instance of TemplateRendererInterface to display an HTML page, so it is listed below.
 */
$container->set(HomeHandler::class);

/*
 * Template renderer definitions
 * -----------------------------
 *
 * Defining the TemplateRendererInterface here so our HomeHandler handler (see upper) can use it when instantiated.
 * The default Borsch Smarty renderer is used.
 * We cache it so that it is not re-created when fetched a second time.
 */
$container->set(TemplateRendererInterface::class, function () {
    $smarty = new Smarty();
    $smarty->setTemplateDir(__DIR__.'/../resources/templates');
    $smarty->setCompileDir(__DIR__.'/../storage/smarty/templates_c');
    $smarty->setCacheDir(__DIR__.'/../storage/smarty/cache');

    return $smarty;
})->cache(true);

/*
 * Database definitions
 * --------------------
 *
 * Borsch uses the laminas-db package, please check it out for more information :
 *     https://docs.laminas.dev/laminas-db/
 * You can update the database informations in the file config/env.ini.
 */
$container->set(AdapterInterface::class, function () {
    return new Adapter(env('DATABASE'));
});

return $container;
