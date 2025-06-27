<?php

use League\Container\Container;

return static function(Container $container): void {
    $container
        ->add(PDO::class)
        ->addArgument('sqlite:'.storage_path('database.sqlite'))
        ->addMethodCall('setAttribute', [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION])
        ->addMethodCall('setAttribute', [PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC])
        ->addMethodCall('setAttribute', [PDO::ATTR_EMULATE_PREPARES, false]);
};
