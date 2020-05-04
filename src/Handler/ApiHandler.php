<?php

namespace App\Handler;


use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ApiHandler
 * @package App\Handler
 */
class ApiHandler implements RequestHandlerInterface
{

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([
            'Context' => sprintf('%s Application', env('APP_NAME', 'Borsch')),
            'Message' => 'JSON Response generated in /api handler.'
        ]);
    }
}
