<?php

use Borsch\Container\Container;

$container = new Container();
$container->setCacheByDefault(true);

(require_once __DIR__ . '/containers/container.handler.php')($container);
(require_once __DIR__ . '/containers/container.middlewares.php')($container);
(require_once __DIR__ . '/containers/container.http.php')($container);
(require_once __DIR__ . '/containers/container.routes.php')($container);
(require_once __DIR__ . '/containers/container.logs.php')($container);
(require_once __DIR__ . '/containers/container.views.php')($container);
(require_once __DIR__ . '/containers/container.database.php')($container);

return $container;
