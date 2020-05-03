<?php

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;

/*
 * Database definitions definitions.
 * Borsch uses the laminas-db package, please check it out for more information.
 * https://docs.laminas.dev/laminas-db/
 */
return [
    AdapterInterface::class => DI\factory(function () {
        return new Adapter([
            'driver' => 'Pdo_Mysql',
            'database' => 'music',
            'username' => 'root',
            'password' => 'root',
            'hostname' => 'localhost',
            'port' => 3306,
            'charset' => 'utf8mb4'
        ]);
    })
];
