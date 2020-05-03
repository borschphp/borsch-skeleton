<?php

$builder = new DI\ContainerBuilder();
$builder->addDefinitions(
    __DIR__.'/definitions/app.php',
    __DIR__.'/definitions/middleware.php',
    __DIR__.'/definitions/db.php'
);

return $builder->build();
