<?php

use App\Listener\MonologListener;
use App\Middleware\ErrorHandlerMiddleware;
use Borsch\Container\Container;
use Monolog\{Formatter\LineFormatter, Handler\StreamHandler, Level, Logger, Processor\PsrLogMessageProcessor};

return static function(Container $container): void {
    $container
        ->set(
            Logger::class,
            fn() => new Logger(
                env('APP_NAME', 'App'),
                [
                    new StreamHandler(
                        logs_path(env('LOG_CHANNEL', 'app').'.log'),
                        constant(Level::class.'::'.ucfirst(env('LOG_LEVEL', 'Debug')))
                    ),
                    (new StreamHandler('php://stdout', Level::Debug))->setFormatter(new LineFormatter(
                        "[%datetime%] %message%\n",
                        'D M j H:i:s Y',
                        true,
                        true
                    ))
                ],
                [new PsrLogMessageProcessor(removeUsedContextFields: true)],
                new DateTimeZone(env('TIMEZONE', 'UTC'))
            )
        )
        -> cache(true);

    $container
        ->set(ErrorHandlerMiddleware::class)
        ->addMethod('addListener', [$container->get(MonologListener::class)]);
};
