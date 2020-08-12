<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SimpleXMLElement;

/**
 * Class BodyParserMiddleware
 * @package App\Middleware
 */
class BodyParserMiddleware implements MiddlewareInterface
{

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $content_type = strtolower(trim($request->getHeaderLine('Content-Type')));
        if (!strlen($content_type)) {
            return $handler->handle($request);
        }

        $content_type = explode(';', $content_type)[0];

        switch ($content_type) {
            case 'application/x-www-form-urlencoded':
                $data = null;
                parse_str((string)$request->getBody(), $data);
                $request = $request->withParsedBody($data);
                break;

            case 'application/json':
                $body = json_decode((string)$request->getBody());
                if (is_array($body)) {
                    $request = $request->withParsedBody($body);
                }
                break;

            case 'application/xml':
            case 'text/xml':
                $backup = libxml_disable_entity_loader(true);
                $backup_errors = libxml_use_internal_errors(true);

                $xml = simplexml_load_string((string)$request->getBody());

                libxml_disable_entity_loader($backup);
                libxml_clear_errors();
                libxml_use_internal_errors($backup_errors);

                if ($xml instanceof SimpleXMLElement) {
                    $request = $request->withParsedBody($xml);
                }
                break;
        }

        return $handler->handle($request);
    }
}
