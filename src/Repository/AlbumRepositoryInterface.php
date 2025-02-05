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

    /** @param array{title: string, artist_id: int} $data */
    public function create(array $data): int;

    /** @param array{title?: string, artist_id?: int} $data */
    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}