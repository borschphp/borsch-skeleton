<?php

use Borsch\Container\Container;
use Laminas\Db\Adapter\{Adapter, AdapterInterface};

return static function (Container $container) {

    $container
        ->set(AdapterInterface::class, Adapter::class)
        ->addParameter([
            'driver' => 'Pdo_Sqlite',
            'dsn' => 'sqlite:'.storage_path('database.sqlite')
        ]);

};
