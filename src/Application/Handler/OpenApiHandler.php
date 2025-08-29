<?php

namespace Application\Handler;

use OpenApi\{Attributes as OA, Generator};
use Borsch\Http\Factory\StreamFactory;
use Borsch\Http\Response;
use Borsch\Router\Attribute\Controller;
use Borsch\Router\Attribute\Get;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

#[OA\Info(
    version: '1.0.0',
    description: 'API for managing albums and artists.',
    title: 'Albums API',
    contact: new OA\Contact('John Doe', email: 'john.doe@example.com'),
)]
#[OA\Server(url: 'http://localhost:8080/api')]
#[Controller('/api')]
readonly class OpenApiHandler implements RequestHandlerInterface
{

    #[Get(path: '/openapi[.{format:json|yaml|yml}]', name: 'openapi')]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $format = $request->getAttribute('format', 'yaml');
        $openapi = (new Generator())->generate([
            __ROOT_DIR__.'/src/Application/Handler',
            __ROOT_DIR__.'/src/Domain',
        ]);
        $definition = match ($format) {
            'json' => $openapi->toJson(),
            default => $openapi->toYaml(),
        };

        $stream_factory  = new StreamFactory();

        return new Response(
            200,
            $stream_factory->createStream($definition),
            ['Content-Type' => ['text/'.$format]]
        );
    }
}
