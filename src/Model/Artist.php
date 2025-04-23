<?php

namespace App\Model;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Artist
{

    #[OA\Property(example: 1, nullable: false)]
    public int $id;

    #[OA\Property(example: 'AC/DC', nullable: false)]
    public string $name;
}