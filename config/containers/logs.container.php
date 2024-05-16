<?php

use App\Listener\MonologListener;
use App\Middleware\ErrorHandlerMiddleware;
use Borsch\Container\Container;
use Monolog\{Handler\StreamHandler, Logger, Processor\PsrLogMessageProcessor};

return static function(Container $container): void {
    $container
        ->set(
            Logger::class,
            fn() => new Logger(
                env('APP_NAME', 'App'),
                [new StreamHandler(
                    logs_path(env('LOG_CHANNEL', 'app').'.log'),
                    constant(Logger::class.'::'.env('LOG_LEVEL', 'DEBUG'))
                )],
                [new PsrLogMessageProcessor(removeUsedContextFields: true)],
                new DateTimeZone(env('TIMEZONE', 'UTC'))
            )
        )
        -> cache(true);

    $container
        ->set(ErrorHandlerMiddleware::class)
        ->addMethod('addListener', [$container->get(MonologListener::class)]);
};
