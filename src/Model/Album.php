<?php

namespace App\Model;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Album extends Model
{

    #[OA\Property(example: 'Minha História', nullable: false)]
    public string $title;

    #[OA\Property(example: 57, nullable: false)]
    public int $artist_id;
}
