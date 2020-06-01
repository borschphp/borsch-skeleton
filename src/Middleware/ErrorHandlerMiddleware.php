<?php

namespace App\Middleware;

use ErrorException;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

/**
 * Class ErrorHandlerMiddleware
 * @package App\Middleware
 */
class ErrorHandlerMiddleware implements MiddlewareInterface
{

    /** @var callable[] */
    protected $listeners = [];

    /**
     * @param callable $listener
     */
    public function addListener(callable $listener): void
    {
        if (!in_array($listener, $this->listeners, true)) {
            $this->listeners[] = $listener;
        }
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): void {
            if (!(error_reporting() & $errno)) {
                // error_reporting does not include this error
                return;
            }

            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        try {
            $response = $handler->handle($request);
        } catch (Throwable $throwable) {
            foreach ($this->listeners as $listener) {
                $listener($throwable, $request);
            }

            $response = (new Response())->withStatus(
                filter_var($throwable->getCode(), FILTER_VALIDATE_INT, [
                    'options' => [
                        'min_range' => 400,
                        'max_range' => 599
                    ]
                ]) ?: 500
            );

            if (env('APP_ENV') != 'production') {
                $response->getBody()->write($throwable->__toString());
            }

            $response->getBody()->write($response->getReasonPhrase() ?: 'Unknown Error');
        }

        restore_error_handler();

        return $response;
    }
}
