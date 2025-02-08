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
readonly class AlbumHandler implements RequestHandlerInterface
{

    public function __construct(
        private AlbumService $service
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
        description: 'Get all albums',
        summary: 'Get all albums',
        tags: ['Albums'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Get all albums',
                content: new OA\JsonContent(
                    examples: [new OA\Examples(example: 'response', summary: 'A list of album', value: [
                        ['id' => 1, 'title' => 'For Those About To Rock We Salute You', 'artist_id' => 1],
                        ['id' => 2, 'title' => 'Balls to the Wall', 'artist_id' => 2],
                        ['id' => 3, 'title' => 'Restless and Wild', 'artist_id' => 2],
                    ])],
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Album')
                )
            ),
            new OA\Response(response: '500', description: 'Internal server error')
        ],
    )]
    #[OA\Get(
        path: '/albums/{id}',
        description: 'Get a specific album',
        summary: 'Get an album by ID',
        tags: ['Albums'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Album ID to retrieve',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', format: 'int32'),
                example: 42
            )
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Get a specific album',
                content: new OA\JsonContent(ref: '#/components/schemas/Album')
            ),
            new OA\Response(response: '404', description: 'Not found'),
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

    #[OA\Post(
        path: '/albums',
        description: 'Create a new album (there is no check on the `artist_id` existence)',
        summary: 'Create a new album',
        requestBody: new OA\RequestBody(
            required: true,
            content: [new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'title', type: 'string'),
                    new OA\Property(property: 'artist_id', type: 'integer', format: 'int32')
                ],
                example: ['title' => 'Minha História', 'artist_id' => 57]
            ))]
        ),
        tags: ['Albums'],
        responses: [
            new OA\Response(
                response: '201',
                description: 'The created album',
                content: new OA\JsonContent(ref: '#/components/schemas/Album')
            ),
            new OA\Response(response: '500', description: 'Internal server error')
        ]
    )]
    /** @param array{title: string, artist_id: int} $body */
    private function createAlbum(array $body): ResponseInterface
    {
        $new_album = $this->service->create($body);

        return new JsonResponse($new_album, 201);
    }

    #[OA\Put(
        path: '/albums/{id}',
        description: 'Update an album by ID',
        summary: 'Update an album',
        requestBody: new OA\RequestBody(
            required: true,
            content: [new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'title', type: 'string'),
                    new OA\Property(property: 'artist_id', type: 'integer', format: 'int32')
                ],
                example: ['title' => 'Minha História', 'artist_id' => 57]
            ))]
        ),
        tags: ['Albums'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Album ID to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', format: 'int32'),
                example: 42
            )
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'The updated album',
                content: new OA\JsonContent(ref: '#/components/schemas/Album')
            ),
            new OA\Response(response: '404', description: 'Not found'),
            new OA\Response(response: '500', description: 'Internal server error')
        ]
    )]
    /** @param array{title?: string, artist_id?: int} $body */
    private function updateAlbum(int $id, array $body): ResponseInterface
    {
        $updated_album = $this->service->update($id, $body);

        return new JsonResponse($updated_album);
    }

    #[OA\Delete(
        path: '/albums/{id}',
        description: 'Delete an album by ID',
        summary: 'Delete an album',
        tags: ['Albums'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Album ID to delete',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', format: 'int32'),
                example: 42
            )
        ],
        responses: [
            new OA\Response(response: '204', description: 'No content'),
            new OA\Response(response: '404', description: 'Not found'),
            new OA\Response(response: '500', description: 'Internal server error')
        ]
    )]
    private function deleteAlbum(int $id): ResponseInterface
    {
        $this->service->delete($id);

        return new EmptyResponse(204);
    }
}
