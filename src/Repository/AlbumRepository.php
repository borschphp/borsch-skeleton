<?php

namespace App\Repository;

use App\Model\Album;
use InvalidArgumentException;
use PDO;
use RuntimeException;

class AlbumRepository implements AlbumRepositoryInterface
{

    public function __construct(
        private PDO $pdo
    ) {}

    /** @return Album[] */
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

    public function create(array $data): int
    {
        if (!isset($data['title'], $data['artist_id'])) {
            throw new InvalidArgumentException('Missing data, "title" and "artist_id" fields are required');
        }

        $stmt = $this
            ->pdo
            ->prepare('INSERT INTO albums (Title, ArtistId) VALUES (?, ?)');

        if (!$stmt->execute([$data['title'], $data['artist_id']])) {
            throw new RuntimeException('Error creating album', 500);
        }

        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $values = [];

        if (isset($data['title'])) {
            $fields[] = 'Title = ?';
            $values[] = $data['title'];
        }

        if (isset($data['artist_id'])) {
            $fields[] = 'ArtistId = ?';
            $values[] = $data['artist_id'];
        }

        if (empty($fields)) {
            throw new InvalidArgumentException('No data to update');
        }

        $values[] = $id;
        $sql = 'UPDATE albums SET '.implode(', ', $fields).' WHERE AlbumId = ?';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($values);
    }

    public function delete(int $id): bool
    {
        $stmt = $this
            ->pdo
            ->prepare('DELETE FROM albums WHERE AlbumId = ?');

        return $stmt->execute([$id]);
    }
}