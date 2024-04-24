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
        $response = $handler->handle($request);

        $message = 'Request: {method} {uri} | Response: {status} {reason}';
        $context = [
            'method' => $request->getMethod(),
            'uri' => (string)$request->getUri(),
            'status' => $response->getStatusCode(),
            'reason' => $response->getReasonPhrase()
        ];

        $this->logger->info($message, $context);

        return $response;
    }
}
