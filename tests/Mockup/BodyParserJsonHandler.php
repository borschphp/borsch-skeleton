<?php

namespace AppTest\Mockup;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BodyParserJsonHandler implements RequestHandlerInterface
{

    /**
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse($request->getParsedBody());
    }
}
