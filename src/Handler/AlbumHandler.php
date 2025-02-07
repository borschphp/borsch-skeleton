<?php

namespace App\Handler;

use App\Service\AlbumService;
use Laminas\Diactoros\Response\{EmptyResponse, JsonResponse};
use OpenApi\Attributes as OA;
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

    #[OA\Get(
        path: '/albums',
        tags: ['Albums'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Get all albums',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Album'))
            ),
            new OA\Response(response: '500', description: 'Internal server error')
        ],
    )]
    #[OA\Get(
        path: '/albums/{id}',
        tags: ['Albums'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Album ID to retrieve',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', format: 'int32')
            )
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Get a specific album',
                content: new OA\JsonContent(ref: '#/components/schemas/Album')
            ),
            new OA\Response(response: '500', description: 'Internal server error')
        ]
    )]
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
