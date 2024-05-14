<?php

require_once __DIR__.'/../vendor/autoload.php';

use Borsch\Application\ApplicationInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

(static function () {
    // Warning, see: https://www.php.net/manual/en/timezones.others.php
    // do not use any of the timezones listed here (besides UTC)
    date_default_timezone_set(env('TIMEZONE', 'UTC'));

    /** @var ContainerInterface $container */
    $container = (require_once __DIR__.'/../config/container.php');

    $app = $container->get(ApplicationInterface::class);

    (require_once __DIR__.'/../config/pipeline.php')($app);
    (require_once __DIR__.'/../config/routes.php')($app);
    (require_once __DIR__.'/../config/api.php')($app);

    $request = $container->get(ServerRequestInterface::class);

    $app->run($request);
})();
