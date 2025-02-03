<?php

namespace App\Repository;

use App\Model\Album;

interface AlbumRepositoryInterface
{

    /**
     * @return Album[]
     */
    public function all(): array;

    public function find(int $id): ?Album;

    public function create(array $data): bool;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}