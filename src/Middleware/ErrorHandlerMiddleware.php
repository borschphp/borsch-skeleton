<?php

namespace App\Middleware;

use App\Formatter\HtmlFormatter;
use App\Formatter\JsonFormatter;
use ErrorException;
use Laminas\Diactoros\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};
use Throwable;

/**
 * Class ErrorHandlerMiddleware
 * @package App\Middleware
 */
class ErrorHandlerMiddleware implements MiddlewareInterface
{

    /** @var callable[] $listeners */
    protected array $listeners = [];

    /** @var callable $formatter */
    protected $formatter;

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $this->setErrorHandler();

            $response = $handler->handle($request);
        } catch (Throwable $throwable) {
            $this->callListeners($throwable, $request);

            $formatter = str_starts_with($request->getUri()->getPath(), '/api/') ?
                new JsonFormatter() : // ProblemDetails
                new HtmlFormatter();  // Whoops

            $response = $formatter(
                $this->getResponseWithStatusCode($throwable),
                $throwable,
                $request
            );
        }

        restore_error_handler();

        return $response;
    }

    /**
     * @param callable $listener
     * @return void
     */
    public function addListener(callable $listener): void
    {
        if (!in_array($listener, $this->listeners, true)) {
            $this->listeners[] = $listener;
        }
    }

    /**
     * @return void
     * @throws ErrorException
     */
    protected function setErrorHandler(): void
    {
        set_error_handler(function(int $errno, string $errstr, string $errfile, int $errline): void {
            if (!(error_reporting() & $errno)) {
                // error_reporting does not include this error
                return;
            }

            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
    }

    /**
     * @param Throwable $throwable
     * @param ServerRequestInterface $request
     * @return void
     */
    protected function callListeners(Throwable $throwable, ServerRequestInterface $request): void
    {
        foreach ($this->listeners as $listener) {
            $listener($throwable, $request);
        }
    }

    /**
     * @param Throwable $throwable
     * @return ResponseInterface
     */
    protected function getResponseWithStatusCode(Throwable $throwable): ResponseInterface
    {
        return (new Response())->withStatus(
            filter_var($throwable->getCode(), FILTER_VALIDATE_INT, [
                'options' => [
                    'min_range' => 400,
                    'max_range' => 599
                ]
            ]) ?: 500
        );
    }
}
