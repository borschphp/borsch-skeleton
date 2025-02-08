<?php

namespace App\Model;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Album
{

    #[OA\Property(example: 42, nullable: false)]
    public int $id;

    #[OA\Property(example: 'Minha História', nullable: false)]
    public string $title;

    #[OA\Property(example: 57, nullable: false)]
    public int $artist_id;
}
