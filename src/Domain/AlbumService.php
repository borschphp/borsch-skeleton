<?php

namespace Domain;

use Domain\Model\Album;
use Infrastructure\AlbumRepository;
use Monolog\Logger;
use ProblemDetails\{ProblemDetails, ProblemDetailsException};

readonly class AlbumService
{

    private Logger $logger;

    public function __construct(
        private AlbumRepository $repository,
        Logger $logger
    ) {
        $this->logger = $logger->withName(__CLASS__);
    }

    /** @return Album[] */
    public function all(): array
    {
        return $this->repository->all();
    }

    /**
     * @throws ProblemDetailsException
     */
    public function find(int $id): ?Album
    {
        $album = $this->repository->find($id);

        if ($album === null) {
            $this->logger->error('Album with ID #{id} not found', ['{id}' => $id]);

            throw new ProblemDetailsException(new ProblemDetails(
                type: '://problem/not-found',
                title: 'Album does not exist.',
                status: 404,
                detail: "The album with ID {$id} could not be found."
            ));
        }

        if (!$album instanceof Album) {
            $this->logger->error('Album with ID #{id} is not an instance of Album', ['{id}' => $id]);

            throw new ProblemDetailsException(new ProblemDetails(
                type: '://problem/invalid-type',
                title: 'Invalid album type.',
                status: 500,
                detail: "The album with ID {$id} is not a valid Album instance."
            ));
        }

        return $album;
    }

    /**
     * @param array{title: string, artist_id: int} $data
     * @throws ProblemDetailsException
     */
    public function create(array $data): Album
    {
        if (!isset($data['title'], $data['artist_id'])) {
            $this->logger->error('Missing required data, unable to save Album, provided data: {data}', ['{data}' => implode(', ', array_keys($data))]);

            throw new ProblemDetailsException(new ProblemDetails(
                type: '://problem/missing-data',
                title: 'Missing data.',
                status: 400,
                detail: 'Missing data, "title" and "artist_id" fields are required.'
            ));
        }

        $id = $this->repository->create(['Title' => $data['title'], 'ArtistId' => $data['artist_id']]);
        if ($id === 0) {
            $this->logger->error('Unable to create album, provided data: {data}', ['{data}' => implode(', ', array_keys($data))]);

            throw new ProblemDetailsException(new ProblemDetails(
                type: '://problem/unable-to-create-resource',
                title: 'Album could not be created.',
                status: 500,
                detail: 'Unable to create the album at the moment, try again later.'
            ));
        }

        return $this->find($id);
    }

    /**
     * @param array{title?: string, artist_id?: int} $data
     * @throws ProblemDetailsException
     */
    public function update(int $id, array $data): Album
    {
        if (!isset($data['title']) && !isset($data['artist_id'])) {
            $this->logger->error('Missing required data, unable to update Album, provided data: {data}', ['{data}' => implode(', ', array_keys($data))]);

            throw new ProblemDetailsException(new ProblemDetails(
                type: '://problem/missing-data',
                title: 'Missing data.',
                status: 400,
                detail: 'Missing data, "title" and "artist_id" fields are required.'
            ));
        }

        // Making sure it exists
        $this->find($id);

        $data = array_filter([
            'Title' => $data['title'] ?? null,
            'ArtistId' => $data['artist_id'] ?? null,
        ]);

        if (!$this->repository->update($id, $data)) {
            $this->logger->error('Album could not be updated with provided data: {data}', ['{data}' => implode(', ', array_keys($data))]);

            throw new ProblemDetailsException(new ProblemDetails(
                type: '://problem/not-updated',
                title: 'Album could not be updated.',
                status: 500,
                detail: 'Unable to update the album at the moment, try again later.'
            ));
        }

        return $this->find($id);
    }

    /**
     * @throws ProblemDetailsException
     */
    public function delete(int $id): bool
    {
        // Making sure it exists
        $this->find($id);

        return $this->repository->delete($id);
    }
}
