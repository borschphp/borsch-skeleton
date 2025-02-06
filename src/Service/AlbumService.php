<?php

namespace App\Service;

use App\Model\Artist;
use App\Repository\AlbumRepository;
use InvalidArgumentException;
use RuntimeException;

readonly class AlbumService
{

    public function __construct(
        private AlbumRepository $repository
    ) {}

    /** @return Artist[] */
    public function all(): array
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Artist
    {
        $album = $this->repository->find($id);
        if ($album === null) {
            throw new RuntimeException('Album does not exist', 404);
        }

        return $this->repository->find($id);
    }

    /** @param array{title: string, artist_id: int} $data */
    public function create(array $data): Artist
    {
        if (!isset($data['title'], $data['artist_id'])) {
            throw new InvalidArgumentException('Missing data, "title" and "artist_id" fields are required');
        }

        $id = $this->repository->create($data);
        if ($id === 0) {
            throw new RuntimeException('Album could not be created', 500);
        }

        return $this->repository->find($id);
    }

    /** @param array{title?: string, artist_id?: int} $data */
    public function update(int $id, array $data): Artist
    {
        if (!isset($data['title']) && !isset($data['artist_id'])) {
            throw new InvalidArgumentException('Missing data, "title" and/or "artist_id" fields are required');
        }

        $album = $this->repository->find($id);
        if ($album === null) {
            throw new RuntimeException('Album does not exist', 404);
        }

        if (!$this->repository->update($id, $data)) {
            throw new RuntimeException('Album could not be updated', 500);
        }

        return $this->repository->find($id);
    }

    public function delete(int $id): bool
    {
        $album = $this->repository->find($id);
        if ($album === null) {
            throw new RuntimeException('Album does not exist', 404);
        }

        return $this->repository->delete($id);
    }
}
