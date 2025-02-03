<?php

namespace App\Repository;

use App\Model\Album;
use App\Repository\AlbumRepositoryInterface;
use PDO;

class AlbumRepository implements AlbumRepositoryInterface
{

    public function __construct(
        private PDO $pdo
    ) {}

    /**
     * @return Album[]
     */
    public function all(): array
    {
        return $this
            ->pdo
            ->query('SELECT AlbumId AS id, Title AS title, ArtistId as artist_id FROM albums')
            ->fetchAll(PDO::FETCH_CLASS, Album::class);
    }

    public function find(int $id): ?Album
    {
        $stmt = $this
            ->pdo
            ->prepare('SELECT AlbumId AS id, Title AS title, ArtistId as artist_id FROM albums WHERE AlbumId = ?');

        $stmt->execute([$id]);

        return $stmt->fetchObject(Album::class) ?: null;
    }

    public function create(array $data): bool
    {
        // TODO: Implement create() method.
    }

    public function update(int $id, array $data): bool
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id): bool
    {
        // TODO: Implement delete() method.
    }
}