<?php

namespace App\Repository;

use App\Model\Model;

interface RepositoryInterface
{

    /** @return Model[] */
    public function all(): array;

    public function find(int $id): ?array;

    /** @param array{title: string, artist_id: int} $data */
    public function create(array $data): int;

    /** @param array{title?: string, artist_id?: int} $data */
    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
