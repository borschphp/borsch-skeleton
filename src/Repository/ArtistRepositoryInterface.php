<?php

namespace App\Repository;

use App\Model\Artist;

interface ArtistRepositoryInterface
{

    /** @return Artist[] */
    public function all(): array;

    public function find(int $id): ?Artist;

    /** @param array{name: string} $data */
    public function create(array $data): int;

    /** @param array{name: string} $data */
    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
