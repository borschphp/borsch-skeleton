<?php

namespace App\Repository;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Monolog\Logger;

abstract readonly class AbstractRepository implements RepositoryInterface
{

    public const ROW_IDENTIFIER = 'id';

    protected TableGateway $table_gateway;

    protected Logger $logger;

    public function __construct(AdapterInterface $adapter, Logger $logger)
    {
        $this->table_gateway = new TableGateway($this->getTable(), $adapter);
        $this->logger = $logger->withName(__CLASS__);
    }

    abstract protected function getTable(): string;

    public function all(): array
    {
        return iterator_to_array($this->table_gateway->select());
    }

    public function find(int $id): ?array
    {
        /** @var ResultSet $results */
        $results = $this->table_gateway->select([static::ROW_IDENTIFIER => $id]);

        return (array)$results->current();
    }

    public function create(array $data): int
    {
        $this->table_gateway->insert($data);

        return $this->table_gateway->getLastInsertValue();
    }

    public function update(int $id, array $data): bool
    {
        return $this->table_gateway->update($data, [static::ROW_IDENTIFIER => $id]) > 0;
    }

    public function delete(int $id): bool
    {
        return $this->table_gateway->delete([static::ROW_IDENTIFIER => $id]) > 0;
    }
}
