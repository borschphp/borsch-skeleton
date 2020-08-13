<?php

require_once __DIR__.'/../vendor/autoload.php';

use Borsch\Application\ApplicationInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/** @var ContainerInterface $container */
$container = (require_once __DIR__.'/../config/container.php');

$app = $container->get(ApplicationInterface::class);

(require_once __DIR__.'/../config/pipeline.php')($app);
(require_once __DIR__.'/../config/routes.php')($app);

$request = $container->get(ServerRequestInterface::class);

$app->run($request);
