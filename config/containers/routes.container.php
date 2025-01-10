<?php

use App\Handler\PeoplesHandler;
use App\Repository\PeopleRepositoryInterface;
use League\Container\Container;

return static function(Container $container): void {
    $container
        ->add(PeoplesHandler::class)
        ->addArgument(PDO::class)
        ->addArgument(PeopleRepositoryInterface::class);
};
