<?php
/*
 * This script was made to be used in the "worker mode" of FrankenPHP.
 * For more details, see the documentation : https://frankenphp.dev/docs/worker/ .
 * ```bash
 * docker run -e FRANKENPHP_CONFIG="worker ./public/worker.php" -v $PWD:/app -p 80:8080 -p 443:443 -p 443:443/udp --tty dunglas/frankenphp
 * ```
 */

ignore_user_abort(true);

require_once __DIR__ . '/../vendor/autoload.php';

use Borsch\RequestHandler\RequestHandlerRunnerInterface;
use Psr\Container\ContainerInterface;

// Warning, see: https://www.php.net/manual/en/timezones.others.php
// do not use any of the timezones listed here (besides UTC)
date_default_timezone_set(env('TIMEZONE', 'UTC'));

/** @var ContainerInterface $container */
$container = (require_once __DIR__ . '/../config/container.php');

$handler = static function () use ($container) {
    $runner = $container->get(RequestHandlerRunnerInterface::class);
    $runner->run();
};

$max_requests_number = filter_input(INPUT_SERVER, 'MAX_REQUESTS', FILTER_SANITIZE_NUMBER_INT) ?: 25;
for ($current_requests_number = 0, $running = true; $current_requests_number < $max_requests_number && $running; ++$current_requests_number) {
    $running = \frankenphp_handle_request($handler);

    gc_collect_cycles();

    if (!$running) {
        break;
    }
}
