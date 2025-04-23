<?php

namespace App\Repository;

use App\Model\Artist;
use InvalidArgumentException;
use Monolog\Logger;
use PDO;
use RuntimeException;

readonly class AlbumRepository implements AlbumRepositoryInterface
{

    private Logger $logger;

    public function __construct(
        private PDO $pdo,
        Logger $logger
    ) {
        $this->logger = $logger->withName(__CLASS__);
    }

    /** @return Artist[] */
    public function all(): array
    {
        return $this
            ->pdo
            ->query('SELECT AlbumId AS id, Title AS title, ArtistId as artist_id FROM albums')
            ->fetchAll(PDO::FETCH_CLASS, Artist::class);
    }

    public function find(int $id): ?Artist
    {
        $stmt = $this
            ->pdo
            ->prepare('SELECT AlbumId AS id, Title AS title, ArtistId as artist_id FROM albums WHERE AlbumId = ?');

        $stmt->execute([$id]);

        return $stmt->fetchObject(Artist::class) ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this
            ->pdo
            ->prepare('INSERT INTO albums (Title, ArtistId) VALUES (?, ?)');

        if (!$stmt->execute([$data['title'], $data['artist_id']])) {
            $this->logger->error('An error occurred while trying to create an album with data: {data}', ['{data}' => implode(', ', array_keys($data))]);
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
            $this->logger->error('No data provided to update Album #{id}', ['{id}' => $id]);
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
