<?php

namespace App\Handler;

use App\Service\ArtistService;
use Laminas\Diactoros\Response\{EmptyResponse, JsonResponse};
use OpenApi\Attributes as OA;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ArtistHandler
 * @package App\Handler
 */
readonly class ArtistHandler implements RequestHandlerInterface
{

    public function __construct(
        private ArtistService $service
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $body = $request->getParsedBody();

        return match ($request->getMethod()) {
            'GET' => $this->getArtists($id),
            'POST' => $this->createArtist($body),
            'PUT', 'PATCH' => $this->updateArtist($id, $body),
            'DELETE' => $this->deleteArtist($id),
            default => new EmptyResponse(405)
        };
    }

    #[OA\Get(
        path: '/artists',
        description: 'Get all artists',
        summary: 'Get all artists',
        tags: ['Artists'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Get all artists',
                content: new OA\JsonContent(
                    examples: [new OA\Examples(example: 'response', summary: 'A list of artist', value: [
                        ['id' => 1, 'name' => 'AC/DC'],
                        ['id' => 2, 'name' => 'Accept'],
                        ['id' => 3, 'name' => 'Aerosmith']
                    ])],
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Artist')
                )
            ),
            new OA\Response(response: '500', description: 'Internal server error')
        ],
    )]
    #[OA\Get(
        path: '/artists/{id}',
        description: 'Get a specific artist',
        summary: 'Get an artist by ID',
        tags: ['Artists'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Artist ID to retrieve',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            )
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Get a specific artist',
                content: new OA\JsonContent(ref: '#/components/schemas/Artist')
            ),
            new OA\Response(response: '404', description: 'Not found'),
            new OA\Response(response: '500', description: 'Internal server error')
        ]
    )]
    private function getArtists(?int $id = null): ResponseInterface
    {
        return new JsonResponse(
            $id !== null ?
                $this->service->find($id) :
                $this->service->all()
        );
    }

    /** @param array{name: string} $body */
    #[OA\Post(
        path: '/artists',
        description: 'Create a new artist',
        summary: 'Create a new artist',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(
                properties: [new OA\Property(property: 'name', type: 'string')],
                example: ['name' => 'AC/DC']
            ))
        ),
        tags: ['Artists'],
        responses: [
            new OA\Response(
                response: '201',
                description: 'Created a new artist',
                content: new OA\JsonContent(ref: '#/components/schemas/Artist')
            ),
            new OA\Response(response: '500', description: 'Internal server error')
        ]
    )]
    private function createArtist(array $body): ResponseInterface
    {
        $new_artist = $this->service->create($body);

        return new JsonResponse($new_artist, 201);
    }

    /** @param array{name: string} $body */
    #[OA\Put(
        path: '/artists/{id}',
        description: 'Update an artist',
        summary: 'Update an artist',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(
                properties: [new OA\Property(property: 'name', type: 'string')],
                example: ['name' => 'AC/DC']
            ))
        ),
        tags: ['Artists'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Artist ID to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            )
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Updated artist',
                content: new OA\JsonContent(ref: '#/components/schemas/Artist')
            ),
            new OA\Response(response: '404', description: 'Not found'),
            new OA\Response(response: '500', description: 'Internal server error')
        ]
    )]
    private function updateArtist(int $id, array $body): ResponseInterface
    {
        $updated_artist = $this->service->update($id, $body);

        return new JsonResponse($updated_artist);
    }

    #[OA\Delete(
        path: '/artists/{id}',
        description: 'Delete an artist',
        summary: 'Delete an artist',
        tags: ['Artists'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Artist ID to delete',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            )
        ],
        responses: [
            new OA\Response(response: '204', description: 'No content'),
            new OA\Response(response: '404', description: 'Not found'),
            new OA\Response(response: '500', description: 'Internal server error')
        ]
    )]
    private function deleteArtist(int $id): ResponseInterface
    {
        $this->service->delete($id);

        return new EmptyResponse(204);
    }
}
