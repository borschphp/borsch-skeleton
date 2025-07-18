<?php

namespace App\Repository;

readonly class AlbumRepository extends AbstractRepository
{

    public const ROW_IDENTIFIER = 'AlbumId';

    protected function getTable(): string
    {
        return 'albums';
    }
}
