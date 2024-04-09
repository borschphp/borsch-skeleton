<?php
/*
 * This script was made to be used in the "worker mode" of FrankenPHP.
 * For more details, see the documentation : https://frankenphp.dev/docs/worker/ .
 * ```bash
 * docker run -e FRANKENPHP_CONFIG="worker ./public/worker.php" -v $PWD:/app -p 80:8080 -p 443:443 -p 443:443/udp dunglas/frankenphp
 * ```
 */

ignore_user_abort(true);

require_once __DIR__ . '/../vendor/autoload.php';

use Borsch\Application\ApplicationInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/** @var ContainerInterface $container */
$container = (require_once __DIR__ . '/../config/container.php');

$app = $container->get(ApplicationInterface::class);

(require_once __DIR__ . '/../config/pipeline.php')($app);
(require_once __DIR__ . '/../config/routes.php')($app);

$handler = static function () use ($app, $container) {
    $request = $container->get(ServerRequestInterface::class);
    $app->run($request);
};

$max_requests_number = filter_input(INPUT_SERVER, 'MAX_REQUESTS', FILTER_SANITIZE_NUMBER_INT) ?: 25;
for ($current_requests_number = 0, $running = true; $current_requests_number < $max_requests_number && $running; ++$current_requests_number) {
    $running = \frankenphp_handle_request($handler);

    gc_collect_cycles();
}
