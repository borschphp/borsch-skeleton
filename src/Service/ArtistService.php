<?php

namespace App\Service;

use App\Model\Artist;
use App\Repository\ArtistRepository;
use App\Repository\Mapper\ArtistMapper;
use ArrayObject;
use InvalidArgumentException;
use Monolog\Logger;
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
            fn(ArrayObject $artist): Artist => ArtistMapper::toArtist($artist),
            $this->repository->all()
        );
    }

    public function find(int $id): ?Artist
    {
        $artist = $this->repository->find($id);
        if ($artist === null) {
            $this->logger->error('Artist with ID #{id} not found', ['{id}' => $id]);
            throw new RuntimeException('Artist does not exist', 404);
        }

        return ArtistMapper::toArtist($artist);
    }

    /** @param array{name: string} $data */
    public function create(array $data): Artist
    {
        if (!isset($data['name'])) {
            $this->logger->error('Missing required data, unable to save Artist, provided data: {data}', ['{data}' => implode(', ', array_keys($data))]);
            throw new InvalidArgumentException('Missing data, "name" field is required');
        }

        $id = $this->repository->create(['Name' => $data['name']]);
        if ($id === 0) {
            $this->logger->error('Unable to create artist, provided data: {data}', ['data' => implode(', ', array_keys($data))]);
            throw new RuntimeException('Artist could not be created', 500);
        }

        return $this->find($id);
    }

    /** @param array{name: string} $data */
    public function update(int $id, array $data): Artist
    {
        if (!isset($data['name'])) {
            $this->logger->error('Missing required data, unable to update Artist, provided data: {data}', ['{data}' => implode(', ', array_keys($data))]);
            throw new InvalidArgumentException('Missing data, "name" field is required');
        }

        $artist = $this->find($id);
        if (!$artist instanceof Artist) {
            $this->logger->error('Artist with ID #{id} not found', ['{id}' => $id]);
            throw new RuntimeException('Artist does not exist', 404);
        }

        if (!$this->repository->update($id, $data)) {
            $this->logger->error('Artist could not be updated with provided data: {data}', ['{data}' => implode(', ', array_keys($data))]);
            throw new RuntimeException('Artist could not be updated', 500);
        }

        return $this->find($id);
    }

    public function delete(int $id): bool
    {
        $artist = $this->find($id);
        if (!$artist instanceof Artist) {
            $this->logger->error('Artist with ID #{id} not found', ['{id}' => $id]);
            throw new RuntimeException('Artist does not exist', 404);
        }

        return $this->repository->delete($id);
    }
}
