<?php

use Borsch\Container\Container;

$container = new Container();

(require_once __DIR__.'/containers/app.container.php')($container);
(require_once __DIR__.'/containers/logs.container.php')($container);
(require_once __DIR__.'/containers/routes.container.php')($container);
(require_once __DIR__.'/containers/pipeline.container.php')($container);
(require_once __DIR__.'/containers/repositories.container.php')($container);
(require_once __DIR__.'/containers/template.container.php')($container);
(require_once __DIR__.'/containers/database.container.php')($container);
(require_once __DIR__.'/containers/cache.container.php')($container);

return $container;
