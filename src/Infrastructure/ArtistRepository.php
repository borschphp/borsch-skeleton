<?php

namespace Infrastructure;

use Infrastructure\Mapper\ArtistMapper;
use Laminas\Db\Adapter\AdapterInterface;
use Monolog\Logger;

readonly class ArtistRepository extends AbstractRepository
{

    public const ROW_IDENTIFIER = 'ArtistId';

    public function __construct(AdapterInterface $adapter, Logger $logger)
    {
        parent::__construct($adapter, new ArtistMapper(), $logger);
    }

    protected function getTable(): string
    {
        return 'artists';
    }
}
