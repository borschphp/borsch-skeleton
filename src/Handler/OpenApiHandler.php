<?php

namespace App\Handler;

use Laminas\Diactoros\Response;
use OpenApi\{Attributes as OA, Generator};
use Laminas\Diactoros\StreamFactory;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

#[OA\Info(
    version: '1.0.0',
    description: 'API for managing albums and artists.',
    title: 'Albums API',
    contact: new OA\Contact('John Doe', email: 'john.doe@example.com'),
)]
#[OA\Server(url: 'http://localhost:8080/api')]
class OpenApiHandler implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $format = $request->getAttribute('format', 'yaml');
        $openapi = Generator::scan([__ROOT_DIR__.'/src']);
        $definition = match ($format) {
            'json' => $openapi->toJson(),
            default => $openapi->toYaml(),
        };

        $stream_factory  = new StreamFactory();

        return new Response(
            $stream_factory->createStream($definition),
            200,
            ['Content-Type' => 'text/'.$format]
        );
    }
}
