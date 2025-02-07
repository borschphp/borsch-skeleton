<?php

namespace App\Model;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Album
{

    #[OA\Property] public int $id;
    #[OA\Property] public string $title;
    #[OA\Property] public int $artist_id;
}
