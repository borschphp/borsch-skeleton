<?php

namespace App\Repository\Mapper;

use App\Model\Album;
use App\Repository\AlbumRepository;

readonly class AlbumMapper
{

    public static function toAlbum($object): Album
    {
        $album = new Album();
        $album->id = $object[AlbumRepository::ROW_IDENTIFIER] ?? null;
        $album->title = $object['Title'] ?? null;
        $album->artist_id = $object['ArtistId'] ?? null;

        return $album;
    }
}
