<?php

namespace App\Middleware;

use Borsch\Router\RouteResultInterface;
use Laminas\Diactoros\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

/**
 * Class ImplicitOptionsMiddleware
 * @package App\Middleware
 */
class ImplicitOptionsMiddleware implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (strtoupper($request->getMethod()) != 'OPTIONS') {
            return $handler->handle($request);
        }

        $result = $request->getAttribute(RouteResultInterface::class);
        if (!$result) {
            return $handler->handle($request);
        }

        if ($result->isFailure() && !$result->isMethodFailure()) {
            return $handler->handle($request);
        }

        if ($result->getMatchedRoute()) {
            return $handler->handle($request);
        }

        $response = new Response();

        return $response->withHeader(
            'Allow',
            implode(', ', $result->getAllowedMethods())
        );
    }
}
