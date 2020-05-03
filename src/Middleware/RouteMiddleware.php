<?php
/**
 * @author debuss-a
 */

namespace App\Middleware;

use Borsch\Router\RouteResultInterface;
use Borsch\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RouteMiddleware
 * @package App\Middleware
 */
class RouteMiddleware implements MiddlewareInterface
{

    /** @var RouterInterface */
    protected $router;

    /**
     * RouteMiddleware constructor.
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
        $request = $request->withAttribute(RouteResultInterface::class, $this->router->match($request));

        return $handler->handle($request);
    }
}
