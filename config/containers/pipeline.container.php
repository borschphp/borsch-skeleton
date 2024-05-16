<?php

use App\Listener\MonologListener;
use App\Middleware\ErrorHandlerMiddleware;
use Borsch\Container\Container;

return static function(Container $container): void {
    $container
        ->set(ErrorHandlerMiddleware::class)
        ->addMethod('addListener', [$container->get(MonologListener::class)]);
};
