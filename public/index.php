<?php

require_once __DIR__.'/../vendor/autoload.php';

use Borsch\Application\ApplicationInterface;
use Psr\Http\Message\ServerRequestInterface;

/** @var DI\Container $container */
$container = (require_once __DIR__.'/../config/container.php');

$app = $container->make(ApplicationInterface::class);

(require_once __DIR__.'/../config/pipeline.php')($app, $container);
(require_once __DIR__.'/../config/routes.php')($app, $container);

$request = $container->get(ServerRequestInterface::class);

$app->run($request);
