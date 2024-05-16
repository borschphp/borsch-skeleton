<?php

use App\Handler\PeoplesHandler;
use Borsch\Container\Container;

return static function(Container $container): void {
    $container->set(PeoplesHandler::class);
};
