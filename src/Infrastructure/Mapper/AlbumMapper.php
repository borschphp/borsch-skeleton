<?php

namespace Infrastructure\Mapper;

use Domain\Model\Album;
use Infrastructure\AlbumRepository;

readonly class AlbumMapper implements MapperInterface
{

    /** @param iterable<string, mixed> $object */
    public function map(iterable $object): Album
    {
        $album = new Album();
        $album->id = $object[AlbumRepository::ROW_IDENTIFIER] ?? null;
        $album->title = $object['Title'] ?? null;
        $album->artist_id = $object['ArtistId'] ?? null;

        return $album;
    }
}
