<?php

namespace App\Model;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Artist extends Model
{

    #[OA\Property(example: 'AC/DC', nullable: false)]
    public string $name;
}