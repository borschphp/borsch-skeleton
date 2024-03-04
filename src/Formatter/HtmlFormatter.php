<?php

namespace App\Formatter;

use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Class HtmlFormatter
 * @package App\Formatter
 */
class HtmlFormatter
{

    public function __invoke(ResponseInterface $response, Throwable $throwable): ResponseInterface
    {
        $response->getBody()->write(sprintf(
            '<strong>On:</strong> %s<br><strong>Response:</strong> %s %s<br>%s<br><br>%s',
            date('c'),
            $response->getStatusCode(),
            $response->getReasonPhrase(),
            !isProduction() ? ('<strong>Error:</strong> '.$throwable->getMessage()) : '',
            !isProduction() ? ('<strong>Stacktrace:</strong><br>'.nl2br($throwable->getTraceAsString())) : ''
        ));

        return $response
            ->withHeader('Content-Type', 'text/html');
    }
}
