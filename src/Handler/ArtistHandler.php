<?php

namespace App\Handler;

use App\Service\ArtistService;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ArtistHandler
 * @package App\Handler
 */
class ArtistHandler implements RequestHandlerInterface
{

    public function __construct(
        private readonly ArtistService $service
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

    private function getArtists(?int $id = null): ResponseInterface
    {
        return new JsonResponse(
            $id !== null ?
                $this->service->find($id) :
                $this->service->all()
        );
    }

    /** @param array{name: string} $body */
    private function createArtist(array $body): ResponseInterface
    {
        $new_artist = $this->service->create($body);

        return new JsonResponse($new_artist, 201);
    }

    /** @param array{name: string} $body */
    private function updateArtist(int $id, array $body): ResponseInterface
    {
        $updated_artist = $this->service->update($id, $body);

        return new JsonResponse($updated_artist);
    }

    private function deleteArtist(int $id): ResponseInterface
    {
        $this->service->delete($id);

        return new EmptyResponse(204);
    }
}
