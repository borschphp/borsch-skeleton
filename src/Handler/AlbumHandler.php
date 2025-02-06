<?php

namespace App\Handler;

use App\Service\AlbumService;
use Laminas\Diactoros\Response\{EmptyResponse, JsonResponse};
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AlbumHandler
 * @package App\Handler
 */
class AlbumHandler implements RequestHandlerInterface
{

    public function __construct(
        private readonly AlbumService $service
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $body = $request->getParsedBody();

        return match ($request->getMethod()) {
            'GET' => $this->getAlbums($id),
            'POST' => $this->createAlbum($body),
            'PUT', 'PATCH' => $this->updateAlbum($id, $body),
            'DELETE' => $this->deleteAlbum($id),
            default => new EmptyResponse(405)
        };
    }

    private function getAlbums(?int $id = null): ResponseInterface
    {
        return new JsonResponse(
            $id !== null ?
                $this->service->find($id) :
                $this->service->all()
        );
    }

    /** @param array{title: string, artist_id: int} $body */
    private function createAlbum(array $body): ResponseInterface
    {
        $new_album = $this->service->create($body);

        return new JsonResponse($new_album, 201);
    }

    /** @param array{title?: string, artist_id?: int} $body */
    private function updateAlbum(int $id, array $body): ResponseInterface
    {
        $updated_album = $this->service->update($id, $body);

        return new JsonResponse($updated_album);
    }

    private function deleteAlbum(int $id): ResponseInterface
    {
        $this->service->delete($id);

        return new EmptyResponse(204);
    }
}
