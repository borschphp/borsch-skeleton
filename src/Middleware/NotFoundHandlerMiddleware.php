<?php

namespace App\Middleware;

use Laminas\Diactoros\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

/**
 * Class NotFoundHandlerMiddleware
 *
 * Generates a 404 Not Found response.
 *
 * @package App\Middleware
 */
class NotFoundHandlerMiddleware implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new Response('php://memory', 404);
    }
}
