<?php

namespace App\Formatter;

use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Class JsonFormatter
 * @package App\Formatter
 */
class JsonFormatter
{

    public function __invoke(ResponseInterface $response, Throwable $throwable): ResponseInterface
    {
        $response->getBody()->write(json_encode(array_filter(
            [
                'status' => $response->getStatusCode(),
                'reason' => $response->getReasonPhrase(),
                'message' => $throwable->getMessage(),
                'date' => date('c')
            ],
            fn($key) => env('APP_ENV') != 'production' || $key != 'message',
            ARRAY_FILTER_USE_KEY
        )));

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
