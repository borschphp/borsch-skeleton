<?php

namespace App\Service;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\TableGateway\TableGateway;
use Monolog\Logger;

readonly abstract class AbstractService
{

    protected TableGateway $table_gateway;
    protected Logger $logger;

    public function __construct(
        protected Adapter $adapter,
        Logger $logger
    ) {
        $this->table_gateway = new TableGateway($this->getTable(), $this->adapter);
        $this->logger = $logger->withName(__CLASS__);
    }

    public function getRowIdentifier(): string
    {
        return 'id';
    }

    abstract protected function getTable(): string;

    public function findAll(): array
    {
        return iterator_to_array($this->table_gateway->select());
    }

    public function findById(int $id): ?array
    {
        $rowset = $this->table_gateway->select([$this->getRowIdentifier() => $id]);
        $row = (array)$rowset->current();
        return $row ?: null;
    }

    public function insert(array $data): int
    {
        $this->table_gateway->insert($data);

        return $this->table_gateway->getLastInsertValue();
    }

    public function update(array $data, array $where): int
    {
        return $this->table_gateway->update($data, $where);
    }

    public function delete(array $where): int
    {
        return $this->table_gateway->delete($where);
    }
}
