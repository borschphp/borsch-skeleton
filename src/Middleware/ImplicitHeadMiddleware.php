<?php

namespace App\Middleware;

use Borsch\Router\RouteResultInterface;
use Borsch\Router\RouterInterface;
use Laminas\Diactoros\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ImplicitHeadMiddleware
 * @package App\Middleware
 */
class ImplicitHeadMiddleware implements MiddlewareInterface
{

    /** @var RouterInterface */
    protected $router;

    /**
     * ImplicitHeadMiddleware constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (strtoupper($request->getMethod()) != 'HEAD') {
            return $handler->handle($request);
        }

        $result = $request->getAttribute(RouteResultInterface::class);
        if (!$result) {
            return $handler->handle($request);
        }

        if ($result->getMatchedRoute()) {
            return $handler->handle($request);
        }

        $route_result = $this->router->match($request->withMethod('GET'));
        if ($route_result->isFailure()) {
            return $handler->handle($request);
        }

        $request = $request
            ->withAttribute(RouteResultInterface::class, $route_result)
            ->withMethod('GET');

        $response = $handler->handle($request);

        return $response->withBody(new Stream(''));
    }
}
