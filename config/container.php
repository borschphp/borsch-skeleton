<?php

$builder = new DI\ContainerBuilder();

if (env('APP_ENV') == 'production') {
    // See: http://php-di.org/doc/performances.html
    // When you deploy new versions of your code to production you must delete the generated file (or the directory that
    // contains it) to ensure that the container is re-compiled.
    $builder->enableCompilation(__DIR__.'/../storage/cache');
}

$builder->addDefinitions(
    __DIR__.'/definitions/app.php',
    __DIR__.'/definitions/middleware.php',
    __DIR__.'/definitions/db.php'
);

return $builder->build();
