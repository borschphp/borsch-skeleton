<?php

namespace App\Formatter;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Class JsonFormatter
 *
 * Returns an RFC 7807 ProblemDetails-like response.
 *
 * @package App\Formatter
 * @see https://datatracker.ietf.org/doc/html/rfc7807
 */
class JsonFormatter
{

    public function __invoke(ResponseInterface $response, Throwable $throwable, RequestInterface $request): ResponseInterface
    {
        $response
            ->getBody()
            ->write(
                json_encode(
                    array_filter([
                        'type' => $this->getRFCSection($response->getStatusCode()),
                        'title' => $response->getReasonPhrase(),
                        'status' => $response->getStatusCode(),
                        'detail' => $throwable->getMessage(),
                        'instance' => $request->getUri()->getPath(),
                        'traceId' => $request->hasHeader('X-Trace-ID') ?
                            $request->getHeader('X-Trace-ID')[0] :
                            null
                    ])
                )
            );

        return $response->withHeader('Content-Type', 'application/json');
    }

    protected function getRFCSection(int $status_code): string
    {
        $uri = 'https://datatracker.ietf.org/doc/html/rfc7231#section-6';

        $uri .= match ($status_code) {
            400 => '.5.1',
            402 => '.5.2',
            403 => '.5.3',
            404 => '.5.4',
            405 => '.5.5',
            406 => '.5.6',
            408 => '.5.7',
            409 => '.5.8',
            410 => '.5.9',
            411 => '.5.10',
            413 => '.5.11',
            414 => '.5.12',
            415 => '.5.13',
            417 => '.5.14',
            426 => '.5.15',
            500 => '.6.1',
            501 => '.6.2',
            502 => '.6.3',
            503 => '.6.4',
            504 => '.6.5',
            505 => '.6.6',
            default => ''
        };

        return $uri;
    }
}
