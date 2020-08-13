<?php

namespace AppTest\Middleware;

use App\Listener\MonologListener;
use App\Middleware\DispatchMiddleware;
use App\Middleware\ErrorHandlerMiddleware;
use App\Middleware\NotFoundHandlerMiddleware;
use App\Middleware\RouteMiddleware;
use AppTest\App;
use Borsch\RequestHandler\RequestHandler;
use Borsch\Router\RouterInterface;
use Laminas\Diactoros\ServerRequestFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use TypeError;

class ErrorHandlerMiddlewareTest extends App
{

    public function __destruct()
    {
        $file = sprintf('%s/%s.log', __DIR__, 'app');
        if (file_exists($file)) {
            @unlink($file);
        }
    }

    public function tearDown(): void
    {
        $file = sprintf('%s/%s.log', __DIR__, 'app');
        if (file_exists($file)) {
            @unlink($file);
        }
    }

    public function testProcessValidListener()
    {
        $error_handler = $this->container->get(ErrorHandlerMiddleware::class);
        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));
        $error_handler->addListener(new MonologListener($logger));

        $this->container->set(ErrorHandlerMiddleware::class, function () use ($error_handler) {
            return $error_handler;
        });

        $app = new \Borsch\Application\App(
            new RequestHandler(),
            $this->container->get(RouterInterface::class),
            $this->container
        );

        $app->pipe(ErrorHandlerMiddleware::class);
        $app->pipe(RouteMiddleware::class);
        $app->pipe(DispatchMiddleware::class);
        $app->pipe(NotFoundHandlerMiddleware::class);

        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/exception');
        $app->runAndGetResponse($server_request);

        $this->assertFileExists($log_file);
    }

    public function testAddInvalidListener()
    {
        $error_handler = $this->container->get(ErrorHandlerMiddleware::class);
        $this->expectException(TypeError::class);
        $error_handler->addListener(null);
    }
}
