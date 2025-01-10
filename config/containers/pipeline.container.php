<?php

use App\Listener\MonologListener;
use App\Middleware\ErrorHandlerMiddleware;
use League\Container\Container;

return static function(Container $container): void {
    $container
        ->add(ErrorHandlerMiddleware::class)
        ->addMethodCall('addListener', [$container->get(MonologListener::class)]);
};
