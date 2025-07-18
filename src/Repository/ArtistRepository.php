<?php

namespace App\Repository;

readonly class ArtistRepository extends AbstractRepository
{

    public const ROW_IDENTIFIER = 'ArtistId';

    protected function getTable(): string
    {
        return 'artists';
    }
}
