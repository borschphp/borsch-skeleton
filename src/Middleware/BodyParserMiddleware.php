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
        if ($this->isNonBodyRequest($request)) {
            return $handler->handle($request);
        }

        $content_type = $this->getContentType($request);

        if ($this->isUrlEncoded($content_type)) {
            $request = $this->getUrlEncodedParsedBody($request);
        } elseif ($this->isJson($content_type)) {
            $request = $this->getJsonParsedBody($request);
        } elseif ($this->isXML($content_type)) {
            $request = $this->getXMLParsedBody($request);
        }

        return $handler->handle($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    protected function isNonBodyRequest(ServerRequestInterface $request): bool
    {
        return in_array($request->getMethod(), [
            'GET',
            'HEAD',
            'OPTIONS'
        ]);
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    protected function getContentType(ServerRequestInterface $request): string
    {
        $content_type = strtolower(trim($request->getHeaderLine('Content-Type')));
        $content_type = explode(';', $content_type)[0];

        return $content_type;
    }

    /**
     * @param string $content_type
     * @return bool
     */
    protected function isUrlEncoded(string $content_type): bool
    {
        return $content_type == 'application/x-www-form-urlencoded';
    }

    /**
     * @param string $content_type
     * @return bool
     */
    protected function isJson(string $content_type): bool
    {
        return $content_type == 'application/json';
    }

    /**
     * @param string $content_type
     * @return bool
     */
    protected function isXML(string $content_type): bool
    {
        return in_array($content_type, [
            'application/xml',
            'text/xml'
        ]);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    protected function getUrlEncodedParsedBody(ServerRequestInterface $request): ServerRequestInterface
    {
        $data = null;
        parse_str((string)$request->getBody(), $data);

        return $request->withParsedBody($data);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    protected function getJsonParsedBody(ServerRequestInterface $request): ServerRequestInterface
    {
        $body = json_decode((string)$request->getBody(), true);
        if (is_array($body)) {
            $request = $request->withParsedBody($body);
        }

        return $request;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    protected function getXMLParsedBody(ServerRequestInterface $request): ServerRequestInterface
    {
        $backup = libxml_disable_entity_loader(true);
        $backup_errors = libxml_use_internal_errors(true);

        $xml = simplexml_load_string((string)$request->getBody());

        libxml_disable_entity_loader($backup);
        libxml_clear_errors();
        libxml_use_internal_errors($backup_errors);

        if ($xml instanceof SimpleXMLElement) {
            $request = $request->withParsedBody($xml);
        }

        return $request;
    }
}
