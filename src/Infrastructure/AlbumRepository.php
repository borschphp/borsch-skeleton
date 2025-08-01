<?php

namespace Infrastructure;

use Infrastructure\Mapper\AlbumMapper;
use Laminas\Db\Adapter\AdapterInterface;
use Monolog\Logger;

readonly class AlbumRepository extends AbstractRepository
{

    public const ROW_IDENTIFIER = 'AlbumId';

    public function __construct(AdapterInterface $adapter, Logger $logger)
    {
        parent::__construct($adapter, new AlbumMapper(), $logger);
    }

    protected function getTable(): string
    {
        return 'albums';
    }
}
