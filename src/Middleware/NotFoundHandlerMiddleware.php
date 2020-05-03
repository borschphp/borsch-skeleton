<?php

namespace App\Middleware;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
        $response = new Response();
        $response = $response->withStatus(404);

        return $response;
    }
}
