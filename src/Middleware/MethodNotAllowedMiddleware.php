<?php

namespace App\Middleware;

use Borsch\Router\RouteResultInterface;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class MethodNotAllowedMiddleware
 * @package App\Middleware
 */
class MethodNotAllowedMiddleware implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route_result = $request->getAttribute(RouteResultInterface::class);
        if (!$route_result || !$route_result->isMethodFailure()) {
            return $handler->handle($request);
        }

        $response = new Response();
        return $response
            ->withStatus(405, 'Method Not Allowed')
            ->withHeader(
                'Allow',
                implode(',', $route_result->getAllowedMethods())
            );
    }
}
