<?php

namespace App\Service;

use App\Model\Album;
use App\Repository\AlbumRepository;
use RuntimeException;

readonly class AlbumService
{

    public function __construct(
        private AlbumRepository $repository
    ) {}

    /** @return Album[] */
    public function all(): array
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Album
    {
        $album = $this->repository->find($id);
        if ($album === null) {
            throw new RuntimeException('Album does not exist', 404);
        }

        return $this->repository->find($id);
    }

    /** @param array{title: string, artist_id: int} $data */
    public function create(array $data): Album
    {
        $id = $this->repository->create($data);
        if ($id === 0) {
            throw new RuntimeException('Album could not be created', 500);
        }

        return $this->repository->find($id);
    }

    /** @param array{title?: string, artist_id?: int} $data */
    public function update(int $id, array $data): Album
    {
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