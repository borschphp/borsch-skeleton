<?php

namespace App\Model;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Model
{

    #[OA\Property(example: 42, nullable: false)]
    public int $id;
}
