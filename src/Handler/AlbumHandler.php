<?php

namespace App\Handler;

use App\Service\AlbumService;
use Laminas\Diactoros\{Response, Response\JsonResponse};
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AlbumHandler
 * @package App\Handler
 */
class AlbumHandler implements RequestHandlerInterface
{

    public function __construct(
        private AlbumService $service
    ) {}

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return match ($request->getMethod()) {
            'GET' => $this->getAlbums($request->getAttribute('id')),
            'POST' => $this->createAlbum(),
            'PUT', 'PATCH' => $this->updateAlbum(),
            'DELETE' => $this->deleteAlbum(),
            default => new Response\EmptyResponse(405),
        };
    }

    private function getAlbums(?int $id = null): JsonResponse
    {
        return new JsonResponse(
            $id !== null ?
                $this->service->find($id) :
                $this->service->all()
        );
    }

    private function createAlbum(): Response
    {
        return new Response(status: 201);
    }

    private function updateAlbum(): Response
    {
        return new Response();
    }

    private function deleteAlbum(): Response
    {
        return new Response();
    }
}
