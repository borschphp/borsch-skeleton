<?php

use App\Repository\{PeopleRepositoryInterface, SQLitePeopleRepository};
use Borsch\Container\Container;

return static function(Container $container): void {
    $container
        ->set(PeopleRepositoryInterface::class, SQLitePeopleRepository::class)
        ->cache(true);
};
