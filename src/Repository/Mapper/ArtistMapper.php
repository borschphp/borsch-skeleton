<?php

namespace App\Repository\Mapper;

use App\Model\Artist;
use App\Repository\ArtistRepository;

class ArtistMapper
{

    public static function toArtist($object): Artist
    {
        $artist = new Artist();
        $artist->id = $object[ArtistRepository::ROW_IDENTIFIER] ?? null;
        $artist->name = $object['Name'] ?? null;

        return $artist;
    }

}
