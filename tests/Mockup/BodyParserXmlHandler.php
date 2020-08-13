<?php

namespace AppTest\Mockup;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BodyParserXmlHandler implements RequestHandlerInterface
{

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $xml = $request->getParsedBody();
        if (!$xml instanceof \SimpleXMLElement) {
            return new JsonResponse([]);
        }

        return new JsonResponse(['ping' => (string)$xml->ping]);
    }
}
