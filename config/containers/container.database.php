<?php

use Borsch\Container\Container;
use Laminas\Db\Adapter\{Adapter, AdapterInterface};

return static function (Container $container) {

    /*
     * An adapter for SQLite database.
     *
     * This adapter uses the `Pdo_Sqlite` driver and connects to an SQLite database file located at
     * `storage/database.sqlite`.
     *
     * It is used by Repositories (in `Infrastructure` namespace) to interact with the SQLite database.
     */
    $container
        ->set(AdapterInterface::class, Adapter::class)
        ->addParameter([
            'driver' => 'Pdo_Sqlite',
            'dsn' => 'sqlite:'.storage_path('database.sqlite')
        ]);

};
