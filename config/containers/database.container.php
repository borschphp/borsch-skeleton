<?php

use Laminas\Db\Adapter\{Adapter, AdapterInterface};
use League\Container\Container;

return static function(Container $container): void {
    $container
        ->add(AdapterInterface::class, Adapter::class)
        ->addArgument([
            'driver' => 'Pdo_Sqlite',
            'dsn' => 'sqlite:'.storage_path('database.sqlite')
        ]);
};
