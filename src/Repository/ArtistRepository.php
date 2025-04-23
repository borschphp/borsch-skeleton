<?php

namespace App\Repository;

use App\Model\Artist;
use Monolog\Logger;
use PDO;
use RuntimeException;

readonly class ArtistRepository implements ArtistRepositoryInterface
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
            ->query('SELECT ArtistId AS id, Name AS name FROM artists')
            ->fetchAll(PDO::FETCH_CLASS, Artist::class);
    }

    public function find(int $id): ?Artist
    {
        $stmt = $this
            ->pdo
            ->prepare('SELECT ArtistId AS id, Name AS name FROM artists WHERE ArtistId = ?');

        $stmt->execute([$id]);

        return $stmt->fetchObject(Artist::class) ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this
            ->pdo
            ->prepare('INSERT INTO artists (Name) VALUES (?)');

        if (!$stmt->execute([$data['name']])) {
            $this->logger->error('An error occurred while trying to create an artist with data: {data}', ['{data}' => implode(', ', array_keys($data))]);
            throw new RuntimeException('Error creating artist', 500);
        }

        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        if (!isset($data['name'])) {
            $this->logger->error('No data provided to update Artist #{id}', ['{id}' => $id]);
            throw new RuntimeException('No fields to update', 400);
        }

        $stmt = $this
            ->pdo
            ->prepare('UPDATE artists SET Name = ? WHERE ArtistId = ?');

        return $stmt->execute([$data['name'], $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this
            ->pdo
            ->prepare('DELETE FROM artists WHERE ArtistId = ?');

        return $stmt->execute([$id]);
    }
}
