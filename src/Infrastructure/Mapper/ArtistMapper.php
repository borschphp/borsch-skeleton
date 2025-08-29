<?php

namespace Infrastructure\Mapper;

use Domain\Model\Artist;
use Infrastructure\ArtistRepository;

readonly class ArtistMapper implements MapperInterface
{

    /** @param iterable<string, mixed> $object */
    public function map(iterable $object): Artist
    {
        $artist = new Artist();
        $artist->id = $object[ArtistRepository::ROW_IDENTIFIER] ?? null;
        $artist->name = $object['Name'] ?? null;

        return $artist;
    }

}
