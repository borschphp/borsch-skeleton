<?php

use App\Handler\{HomeHandler};
use Borsch\Application\Application;

/**
 * @param Application $app
 * @see https://github.com/nikic/FastRoute
 */
return static function(Application $app): void {
    $app->get('/', HomeHandler::class, 'home');
};
