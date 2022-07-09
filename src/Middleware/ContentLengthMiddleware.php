<?php

namespace App\Middleware;

use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

/**
 * Class ContentLengthMiddleware
 * @package App\Middleware
 */
class ContentLengthMiddleware implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $body_size = $response->getBody()->getSize();

        if ($response->hasHeader('Content-Length') || $body_size === null) {
            return $response;
        }

        return $response->withHeader('Content-Length', (string)$body_size);
    }
}
