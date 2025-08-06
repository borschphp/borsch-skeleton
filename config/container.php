<?php

use Borsch\Container\Container;

$container = new Container();
$container->setCacheByDefault(true);

(require_once __DIR__.'/container.handler.php')($container);
(require_once __DIR__.'/container.middlewares.php')($container);
(require_once __DIR__.'/container.http.php')($container);
(require_once __DIR__.'/container.routes.php')($container);
(require_once __DIR__.'/container.logs.php')($container);
(require_once __DIR__.'/container.views.php')($container);

return $container;
