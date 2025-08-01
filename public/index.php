<?php

require_once __DIR__.'/../vendor/autoload.php';

use Borsch\RequestHandler\RequestHandlerRunnerInterface;
use Psr\Container\ContainerInterface;

(static function () {
    // Warning, see: https://www.php.net/manual/en/timezones.others.php
    // do not use any of the timezones listed here (besides UTC)
    date_default_timezone_set(env('TIMEZONE', 'UTC'));

    /** @var ContainerInterface $container */
    $container = (require_once __DIR__.'/../config/container.php');

    $runner = $container->get(RequestHandlerRunnerInterface::class);
    $runner->run();
})();
