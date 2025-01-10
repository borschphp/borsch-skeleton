<?php

use App\Listener\MonologListener;
use App\Middleware\ErrorHandlerMiddleware;
use Borsch\Container\Container;
use Monolog\{Handler\StreamHandler, Level, Logger, Processor\PsrLogMessageProcessor};

return static function(Container $container): void {
    $container->set(Logger::class, function (): Logger {
        $name = env('APP_NAME', 'App');

        $handlers = [
            new StreamHandler(
                logs_path(env('LOG_CHANNEL', 'app').'.log'),
                constant(Level::class.'::'.ucfirst(env('LOG_LEVEL', 'Debug')))
            )
        ];

        $processors = [new PsrLogMessageProcessor(removeUsedContextFields: true)];
        $datetime_zone = new DateTimeZone(env('TIMEZONE', 'UTC'));

        return new Logger($name, $handlers, $processors, $datetime_zone);
    })-> cache(true);

    $container
        ->set(ErrorHandlerMiddleware::class)
        ->addMethod('addListener', [$container->get(MonologListener::class)]);
};
