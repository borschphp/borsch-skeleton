<?php

use App\Handler\{HomeHandler};
use Borsch\Application\App;

/**
 * @param App $app
 * @see https://github.com/nikic/FastRoute
 */
return static function(App $app): void {
    $app->get('/', HomeHandler::class, 'home');
};
