<?php

namespace AppTest;

use App\Middleware\{BodyParserMiddleware,
    ContentLengthMiddleware,
    DispatchMiddleware,
    ErrorHandlerMiddleware,
    ImplicitHeadMiddleware,
    ImplicitOptionsMiddleware,
    MethodNotAllowedMiddleware,
    NotFoundHandlerMiddleware,
    RouteMiddleware,
    TrailingSlashMiddleware};
use AppTest\Mockup\{BodyParserJsonHandler,
    BodyParserUrlEncodedHandler,
    BodyParserXmlHandler,
    ExceptionGeneratorHandler,
    HeadHandler,
    RequestHeadersResponseHandler,
    TestHandler};
use Borsch\Application\{App as BorschApp, ApplicationInterface};
use Borsch\Container\Container;
use Borsch\RequestHandler\RequestHandler;
use Borsch\Router\{FastRouteRouter, RouterInterface};
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App extends TestCase
{

    /** @var ApplicationInterface */
    protected ApplicationInterface $app;

    /** @var ContainerInterface */
    protected ContainerInterface $container;

    /**
     * App constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $container = new Container();
        $container->set(RouteMiddleware::class);
        $container->set(DispatchMiddleware::class);
        $container->set(NotFoundHandlerMiddleware::class);
        $container->set(FastRouteRouter::class);
        $container->set(RouterInterface::class, FastRouteRouter::class)->cache(true);

        $this->container = $container;
        $this->app = new class(new RequestHandler(), $container->get(RouterInterface::class), $container) extends BorschApp {
            public function runAndGetResponse(ServerRequestInterface $server_request): ResponseInterface
            {
                return $this->request_handler->handle($server_request);
            }
        };

        // Middlewares pipeline
        $this->app->pipe(ErrorHandlerMiddleware::class);
        $this->app->pipe(TrailingSlashMiddleware::class);
        $this->app->pipe(ContentLengthMiddleware::class);

        $this->app->pipe('/to/post', BodyParserMiddleware::class);

        $this->app->pipe(RouteMiddleware::class);
        $this->app->pipe(ImplicitHeadMiddleware::class);
        $this->app->pipe(ImplicitOptionsMiddleware::class);
        $this->app->pipe(MethodNotAllowedMiddleware::class);
        $this->app->pipe(DispatchMiddleware::class);
        $this->app->pipe(NotFoundHandlerMiddleware::class);

        // Routes
        $this->app->get('/to/dispatch', TestHandler::class);

        $this->app->get('/to/route', TestHandler::class);
        $this->app->post('/to/route', TestHandler::class);
        $this->app->put('/to/route', TestHandler::class);
        $this->app->delete('/to/route', TestHandler::class);
        $this->app->head('/to/route', TestHandler::class);
        $this->app->options('/to/route', TestHandler::class);
        $this->app->patch('/to/route', TestHandler::class);
        $this->app->connect('/to/route', TestHandler::class);
        $this->app->trace('/to/route', TestHandler::class);
        $this->app->purge('/to/route', TestHandler::class);
        $this->app->any('/to/any', TestHandler::class);
        $this->app->match(['POST', 'PUT'], '/to/match', TestHandler::class);

        $this->app->get('/to/exception', ExceptionGeneratorHandler::class);

        $this->app->post('/to/head/without/post', TestHandler::class);
        $this->app->post('/to/post/and/check/json', BodyParserJsonHandler::class);
        $this->app->post('/to/post/and/check/urlencoded', BodyParserUrlEncodedHandler::class);
        $this->app->post('/to/post/and/check/xml', BodyParserXmlHandler::class);

        $this->app->head('/to/head', HeadHandler::class);
        $this->app->options('/to/options', RequestHeadersResponseHandler::class);
    }
}
