<?php

namespace App\Middleware;

use Monolog\Logger;
use PHP_Parallel_Lint\PhpConsoleColor\ConsoleColor;
use PHP_Parallel_Lint\PhpConsoleColor\InvalidStyleException;
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
     * @throws InvalidStyleException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        // Equivalent to cli command: php -S localhost:8000 -t public
        if (!defined('STDOUT')) {
            define('STDOUT', fopen('php://stdout', 'w'));
        }

        $console_color = new ConsoleColor();
        $message = '{ip}:{port} [{status}]: {method} {uri}';

        if ($response->getStatusCode() >= 500) {
            $message = $console_color->apply('red', $message);
        } elseif ($response->getStatusCode() >= 400) {
            $message = $console_color->apply('yellow', $message);
        } elseif ($response->getStatusCode() >= 300) {
            $message = $console_color->apply('cyan', $message);
        } elseif ($response->getStatusCode() >= 200) {
            $message = $console_color->apply('green', $message);
        }

        $context = [
            'ip' => $request->getUri()->getHost(),
            'port' => $request->getUri()->getPort(),
            'status' => $response->getStatusCode(),
            'method' => $request->getMethod(),
            'uri' => $request->getUri()->getPath().(strlen($request->getUri()->getQuery()) ? '?'.$request->getUri()->getQuery() : '')
        ];

        $this->logger->info($message, $context);

        return $response;
    }
}
