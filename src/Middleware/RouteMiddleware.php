<?php

namespace App\Middleware;

use Borsch\Router\{RouteResultInterface, RouterInterface};
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

/**
 * Class RouteMiddleware
 * @package App\Middleware
 */
class RouteMiddleware implements MiddlewareInterface
{

    /**
     * RouteMiddleware constructor.
     * @param RouterInterface $router
     */
    public function __construct(
        protected RouterInterface $router
    ) {}

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute(RouteResultInterface::class, $this->router->match($request));

        return $handler->handle($request);
    }
}
