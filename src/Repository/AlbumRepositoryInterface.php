<?php

namespace App\Repository;

use App\Model\Artist;

interface AlbumRepositoryInterface
{

    /** @return Artist[] */
    public function all(): array;

    public function find(int $id): ?Artist;

    /** @param array{title: string, artist_id: int} $data */
    public function create(array $data): int;

    /** @param array{title?: string, artist_id?: int} $data */
    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
