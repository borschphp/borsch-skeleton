<?php

use App\Handler\HomeHandler;
use App\Handler\UserHandler;
use Borsch\Application\App;

/**
 * @param App $app
 * @see https://github.com/nikic/FastRoute
 */
return function (App $app): void {
    $app->get('/', HomeHandler::class, 'home');
    $app->get('/user[/{id:\d+}]', UserHandler::class, 'user');
};
