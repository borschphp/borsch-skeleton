<?php

use League\Container\Container;

return static function(Container $container): void {
    $container->add(PDO::class, function () {
        $pdo = new PDO('sqlite:'.storage_path('database.sqlite'));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $pdo;
    });
};
