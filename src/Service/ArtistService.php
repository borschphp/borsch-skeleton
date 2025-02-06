<?php

namespace App\Service;

use App\Model\Artist;
use App\Repository\ArtistRepository;
use InvalidArgumentException;
use RuntimeException;

readonly class ArtistService
{

    public function __construct(
        private ArtistRepository $repository
    ) {}

    /** @return Artist[] */
    public function all(): array
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Artist
    {
        $artist = $this->repository->find($id);
        if ($artist === null) {
            throw new RuntimeException('Artist does not exist', 404);
        }

        return $artist;
    }

    /** @param array{name: string} $data */
    public function create(array $data): Artist
    {
        if (!isset($data['name'])) {
            throw new InvalidArgumentException('Missing data, "name" field is required');
        }

        $id = $this->repository->create($data);
        if ($id === 0) {
            throw new RuntimeException('Artist could not be created', 500);
        }

        return $this->repository->find($id);
    }

    /** @param array{name: string} $data */
    public function update(int $id, array $data): Artist
    {
        if (!isset($data['name'])) {
            throw new InvalidArgumentException('Missing data, "name" field is required');
        }

        $artist = $this->repository->find($id);
        if ($artist === null) {
            throw new RuntimeException('Artist does not exist', 404);
        }

        if (!$this->repository->update($id, $data)) {
            throw new RuntimeException('Artist could not be updated', 500);
        }

        return $this->repository->find($id);
    }

    public function delete(int $id): bool
    {
        $artist = $this->repository->find($id);
        if ($artist === null) {
            throw new RuntimeException('Artist does not exist', 404);
        }

        return $this->repository->delete($id);
    }
}
