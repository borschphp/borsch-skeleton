<?php

namespace App\Service;

use App\Model\Album;
use App\Repository\AlbumRepository;

class AlbumService
{

    public function __construct(
        private AlbumRepository $repository
    ) {}

    public function all(): array
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Album
    {
        $album = $this->repository->find($id);
        if ($album === null) {
            throw new \RuntimeException('Album does not exist', 404);
        }

        return $this->repository->find($id);
    }
}