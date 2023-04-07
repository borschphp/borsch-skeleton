<?php

namespace App\Middleware;

use Monolog\Logger;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

/**
 * Class LogMiddleware
 * @package App\Middleware
 */
class LogMiddleware implements MiddlewareInterface
{

    public function __construct(
        protected Logger $logger
    ) {}

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->logger->info(strtr('Request: {method} {uri}', [
            '{method}' => $request->getMethod(),
            '{uri}' => (string)$request->getUri()
        ]));

        $response = $handler->handle($request);

        $this->logger->info(strtr('Response: {status} {reason}', [
            '{status}' => $response->getStatusCode(),
            '{reason}' => $response->getReasonPhrase()
        ]));

        return $response;
    }
}
