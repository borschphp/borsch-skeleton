<?php

use Borsch\Router\RouterInterface;
use League\Container\Container;
use App\Repository\{PeopleRepositoryInterface, SQLitePeopleRepository};

return static function(Container $container): void {
    $container
        ->add(PeopleRepositoryInterface::class, SQLitePeopleRepository::class)
        ->addArgument(PDO::class)
        ->addArgument(RouterInterface::class);
};
