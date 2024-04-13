<?php

use App\Listener\MonologListener;
use App\Middleware\BodyParserMiddleware;
use App\Middleware\ContentLengthMiddleware;
use App\Middleware\DispatchMiddleware;
use App\Middleware\ErrorHandlerMiddleware;
use App\Middleware\ImplicitHeadMiddleware;
use App\Middleware\ImplicitOptionsMiddleware;
use App\Middleware\MethodNotAllowedMiddleware;
use App\Middleware\NotFoundHandlerMiddleware;
use App\Middleware\RouteMiddleware;
use App\Middleware\TrailingSlashMiddleware;
use App\Template\LatteEngine;
use AppTest\Mock\TestHandler;
use Borsch\Application\App as BorschApp;
use Borsch\Container\Container;
use Borsch\RequestHandler\RequestHandler;
use Borsch\Router\FastRouteRouter;
use Borsch\Router\RouterInterface;
use Borsch\Template\TemplateRendererInterface;
use Laminas\Diactoros\ServerRequestFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
*/

uses()
    ->beforeEach(function () {
        $this->log_file = __DIR__.'/app.log';
        $this->logger = new Logger('Borsch');
        $this->logger->pushHandler(new StreamHandler($this->log_file));

        $this->listener = new MonologListener($this->logger);

        $this->server_request = (new ServerRequestFactory())->createServerRequest(
            'GET',
            'https://example.com/to/dispatch'
        );
    })
    ->afterEach(function () {
        if (file_exists($this->log_file)) {
            @unlink($this->log_file);
        }
    })
    ->in(
        'Unit/Listener/MonologListenerTest.php',
        'Unit/Middleware/ErrorHandlerMiddlewareTest.php'
    );

uses()
    ->beforeEach(function () {
        $this->log_file = __DIR__.'/app.log';

        $container = new Container();
        $container->set(RouteMiddleware::class);
        $container->set(DispatchMiddleware::class);
        $container->set(NotFoundHandlerMiddleware::class);
        $container->set(FastRouteRouter::class);
        $container->set(RouterInterface::class, FastRouteRouter::class)->cache(true);
        $container->set(
            Logger::class,
            fn() => (new Logger(env('APP_NAME', 'App')))
                ->pushHandler(new StreamHandler(__DIR__.'/app.log'))
        );
        $container
            ->set(ErrorHandlerMiddleware::class)
            ->addMethod('addListener', [$container->get(MonologListener::class)]);
        $container
            ->set(TemplateRendererInterface::class, LatteEngine::class);

        $this->container = $container;
        $this->app = new class(new RequestHandler(), $container->get(RouterInterface::class), $container) extends BorschApp {
            public function runAndGetResponse(ServerRequestInterface $server_request): ResponseInterface
            {
                return $this->request_handler->handle($server_request);
            }
            public function getContainer(): Container
            {
                return $this->container;
            }
        };

        // Middlewares pipeline
        $this->app->pipe(ErrorHandlerMiddleware::class);
        $this->app->pipe(TrailingSlashMiddleware::class);
        $this->app->pipe(ContentLengthMiddleware::class);
        $this->app->pipe('/to/post/and/check', BodyParserMiddleware::class);
        $this->app->pipe(RouteMiddleware::class);
        $this->app->pipe(ImplicitHeadMiddleware::class);
        $this->app->pipe(ImplicitOptionsMiddleware::class);
        $this->app->pipe(MethodNotAllowedMiddleware::class);
        $this->app->pipe(DispatchMiddleware::class);
        $this->app->pipe(NotFoundHandlerMiddleware::class);

        // Routes
        $this->app->get('/to/dispatch', TestHandler::class);
        $this->app->get('/to/exception', TestHandler::class);
        $this->app->post('/to/post/and/check/json', TestHandler::class);
        $this->app->post('/to/post/and/check/urlencoded', TestHandler::class);
        $this->app->post('/to/post/and/check/xml', TestHandler::class);
        $this->app->post('/to/head/without/post', TestHandler::class);
        $this->app->head('/to/head', TestHandler::class);
        $this->app->options('/to/options', TestHandler::class);
        $this->app->get('/to/route/result', TestHandler::class);

        // Server Requests
        $factory = new ServerRequestFactory();
        $this->server_request = $factory->createServerRequest('GET', 'https://example.com/to/dispatch');
        $this->server_request_not_found = $factory->createServerRequest('GET', 'https://example.com/to/not/dispatch');
        $this->server_request_to_exception = $factory->createServerRequest('GET', 'https://example.com/to/exception');
    })
    ->in('Unit/Middleware');

uses()
    ->beforeEach(function () {
        $_ENV['TEST1'] = 'true';
        $_ENV['TEST2'] = 'yes';
        $_ENV['TEST3'] = 'false';
        $_ENV['TEST4'] = 'no';
        $_ENV['TEST5'] = 'empty';
        $_ENV['TEST6'] = 'null';
        $_ENV['TEST7'] = 'a value   ';
    })
    ->in('Unit/Bootstrap/HelpersTest.php');
