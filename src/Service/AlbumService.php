<?php

namespace App\Service;

use App\Model\Album;
use App\Model\Artist;
use App\Repository\AlbumRepository;
use App\Repository\Mapper\AlbumMapper;
use ArrayObject;
use InvalidArgumentException;
use Monolog\Logger;
use RuntimeException;

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
        return array_map(
            fn(ArrayObject $album): Album => AlbumMapper::toAlbum($album),
            $this->repository->all()
        );
    }

    public function find(int $id): ?Album
    {
        $album = $this->repository->find($id);
        if ($album === null) {
            $this->logger->error('Album with ID #{id} not found', ['{id}' => $id]);
            throw new RuntimeException('Album does not exist', 404);
        }

        return AlbumMapper::toAlbum($album);
    }

    /** @param array{title: string, artist_id: int} $data */
    public function create(array $data): Album
    {
        if (!isset($data['title'], $data['artist_id'])) {
            $this->logger->error('Missing required data, unable to save Album, provided data: {data}', ['{data}' => implode(', ', array_keys($data))]);
            throw new InvalidArgumentException('Missing data, "title" and "artist_id" fields are required');
        }

        $id = $this->repository->create(['Title' => $data['title'], 'ArtistId' => $data['artist_id']]);
        if ($id === 0) {
            $this->logger->error('Unable to create album, provided data: {data}', ['{data}' => implode(', ', array_keys($data))]);
            throw new RuntimeException('Album could not be created', 500);
        }

        return $this->find($id);
    }

    /** @param array{title?: string, artist_id?: int} $data */
    public function update(int $id, array $data): Album
    {
        if (!isset($data['title']) && !isset($data['artist_id'])) {
            $this->logger->error('Missing required data, unable to update Album, provided data: {data}', ['{data}' => implode(', ', array_keys($data))]);
            throw new InvalidArgumentException('Missing data, "title" and/or "artist_id" fields are required');
        }

        $album = $this->find($id);
        if (!$album instanceof Album) {
            $this->logger->error('Album with ID #{id} not found', ['{id}' => $id]);
            throw new RuntimeException('Album does not exist', 404);
        }

        $data = array_filter([
            'Title' => $data['title'] ?? null,
            'ArtistId' => $data['artist_id'] ?? null,
        ]);

        if (!$this->repository->update($id, $data)) {
            $this->logger->error('Album could not be updated with provided data: {data}', ['{data}' => implode(', ', array_keys($data))]);
            throw new RuntimeException('Album could not be updated', 500);
        }

        return $this->find($id);
    }

    public function delete(int $id): bool
    {
        $album = $this->find($id);
        if (!$album instanceof Album) {
            $this->logger->error('Album with ID #{id} not found', ['{id}' => $id]);
            throw new RuntimeException('Album does not exist', 404);
        }

        return $this->repository->delete($id);
    }
}
