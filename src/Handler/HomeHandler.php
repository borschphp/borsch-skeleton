<?php

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class HomeHandler
 * @package App\Handler
 */
class HomeHandler implements RequestHandlerInterface
{

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(sprintf(
            'Hello %s !',
            ($request->getQueryParams()['name'] ?? $request->getHeaderLine('X-Name')) ?: 'World'
        ));
    }
}
