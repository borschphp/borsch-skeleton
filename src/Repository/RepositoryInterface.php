<?php

namespace App\Repository;

use App\Model\Model;

interface RepositoryInterface
{

    /** @return array<array<string, mixed>> */
    public function all(): array;

    /** @return array<string, mixed>|null */
    public function find(int $id): ?array;

    /** @param array{Title?: string, ArtistId?: int, Name?: string} $data */
    public function create(array $data): int;

    /** @param array{Title?: string, ArtistId?: int} $data */
    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
