<?php

namespace App\Repository;

use App\Model\People;
use Borsch\Router\RouterInterface;
use PDO;
use ReflectionClass;
use ReflectionException;

/**
 * Class SQLitePeopleRepository
 */
class SQLitePeopleRepository implements PeopleRepositoryInterface
{

    public function __construct(
        protected PDO $pdo,
        protected RouterInterface $router
    ) {}

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        $statement = $this->pdo->prepare('SELECT * FROM `peoples` ORDER BY `id` LIMIT 10');
        $statement->execute();

        return array_map([$this, 'hydrate'], $statement->fetchAll());
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ?People
    {
        $statement = $this->pdo->prepare('SELECT * FROM `peoples` WHERE `id` = ?');
        $statement->execute([$id]);

        $result = $statement->fetch();
        if (!is_array($result) || !count($result)) {
            return null;
        }

        return $this->hydrate($result);
    }

    public function create($body): People
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO `peoples` (`name`, `height`, `birth_year`, `gender`)
            VALUES (:name, :height, :birth_year, :gender)'
        );

        $statement->execute($body);

        $id = $this->getLastInsertedRowId();

        return $this->getById($id);
    }

    public function update(int $id, array $body): People
    {
        $query = 'UPDATE `peoples`';

        $columns = [];
        foreach (array_keys($body) as $column) {
            $columns[] = sprintf('`%s` = :%s', $column, $column);
        }
        $columns[] = sprintf('`updated_at` = "%s"', date('Y-m-d H:i:s'));

        $query .= ' SET '.implode(', ', $columns).' WHERE `id` = :id';

        $statement = $this->pdo->prepare($query);
        $statement->execute(array_merge(['id' => $id], $body));

        return $this->getById($id);
    }

    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM `peoples` WHERE `id` = ?');
        if (!$statement->execute([$id])) {
            return false;
        }

        return true;
    }

    /**
     * @return int
     */
    private function getLastInsertedRowId(): int
    {
        $statement = $this->pdo->prepare('SELECT last_insert_rowid()');
        $statement->execute();

        return (int)$statement->fetchColumn();
    }

    /**
     * @param array $data
     * @return People
     * @throws ReflectionException
     */
    private function hydrate(array $data): People
    {
        /** @var People $people */
        $people = (new ReflectionClass(People::class))->newInstanceArgs($data);

        $people->setLink(sprintf(
            '%s%s',
            rtrim(env('APP_URL'), '/'),
            $this->router->generateUri('peoples', $data)
        ));

        return $people;
    }
}
