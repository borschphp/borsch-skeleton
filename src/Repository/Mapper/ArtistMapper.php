<?php

namespace App\Repository\Mapper;

use App\Model\Artist;
use App\Repository\ArtistRepository;

class ArtistMapper
{

    /** @param iterable<string, mixed> $object */
    public static function toArtist(iterable $object): Artist
    {
        $artist = new Artist();
        $artist->id = $object[ArtistRepository::ROW_IDENTIFIER] ?? null;
        $artist->name = $object['Name'] ?? null;

        return $artist;
    }

}
