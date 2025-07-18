<?php

namespace App\Service;

use App\Model\Artist;
use App\Repository\ArtistRepository;
use App\Repository\Mapper\ArtistMapper;
use InvalidArgumentException;
use Monolog\Logger;
use ProblemDetails\ProblemDetails;
use ProblemDetails\ProblemDetailsException;
use RuntimeException;

readonly class ArtistService
{

    private Logger $logger;

    public function __construct(
        private ArtistRepository $repository,
        Logger $logger
    ) {
        $this->logger = $logger->withName(__CLASS__);
    }

    /** @return Artist[] */
    public function all(): array
    {
        return array_map(
            fn(iterable $artist): Artist => ArtistMapper::toArtist($artist),
            $this->repository->all()
        );
    }

    public function find(int $id): ?Artist
    {
        $artist = $this->repository->find($id);
        if ($artist === null) {
            $this->logger->error('Artist with ID #{id} not found', ['{id}' => $id]);

            throw new ProblemDetailsException(new ProblemDetails(
                type: '://problem/not-found',
                title: 'Artist does not exist.',
                status: 404,
                detail: "The artist with ID {$id} could not be found."
            ));
        }

        return ArtistMapper::toArtist($artist);
    }

    /** @param array{name: string} $data */
    public function create(array $data): Artist
    {
        if (!isset($data['name'])) {
            $this->logger->error('Missing required data, unable to save Artist, provided data: {data}', ['{data}' => implode(', ', array_keys($data))]);

            throw new ProblemDetailsException(new ProblemDetails(
                type: '://problem/missing-data',
                title: 'Missing data.',
                status: 400,
                detail: 'Missing data, "name" field is required.'
            ));
        }

        $id = $this->repository->create(['Name' => $data['name']]);
        if ($id === 0) {
            $this->logger->error('Unable to create artist, provided data: {data}', ['data' => implode(', ', array_keys($data))]);

            throw new ProblemDetailsException(new ProblemDetails(
                type: '://problem/unable-to-create-resource',
                title: 'Artist could not be created.',
                status: 500,
                detail: 'Unable to create the artist at the moment, try again later.'
            ));
        }

        return $this->find($id);
    }

    /** @param array{name: string} $data */
    public function update(int $id, array $data): Artist
    {
        if (!isset($data['name'])) {
            $this->logger->error('Missing required data, unable to update Artist, provided data: {data}', ['{data}' => implode(', ', array_keys($data))]);

            throw new ProblemDetailsException(new ProblemDetails(
                type: '://problem/missing-data',
                title: 'Missing data.',
                status: 400,
                detail: 'Missing data, "name" field is required.'
            ));
        }

        // Making sure it exists
        $this->find($id);

        if (!$this->repository->update($id, $data)) {
            $this->logger->error('Artist could not be updated with provided data: {data}', ['{data}' => implode(', ', array_keys($data))]);

            throw new ProblemDetailsException(new ProblemDetails(
                type: '://problem/not-updated',
                title: 'Artist could not be updated.',
                status: 500,
                detail: 'Unable to update the artist at the moment, try again later.'
            ));
        }

        return $this->find($id);
    }

    public function delete(int $id): bool
    {
        // Making sure it exists
        $this->find($id);

        return $this->repository->delete($id);
    }
}
