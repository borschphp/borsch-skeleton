<?php

use League\Container\{Container, ReflectionContainer};

$container = new Container();

$container->defaultToShared();
$container->delegate(new ReflectionContainer(true));

(require_once __DIR__.'/containers/app.container.php')($container);
(require_once __DIR__.'/containers/logs.container.php')($container);
(require_once __DIR__.'/containers/routes.container.php')($container);
(require_once __DIR__.'/containers/pipeline.container.php')($container);
(require_once __DIR__.'/containers/repositories.container.php')($container);
(require_once __DIR__.'/containers/database.container.php')($container);

return $container;
