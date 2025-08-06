<?php

use Borsch\Container\Container;
use Monolog\Handler\StreamHandler;
use Monolog\{Level, Logger};
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Log\LoggerInterface;

return static function (Container $container) {

    $container->set(LoggerInterface::class, Logger::class);

    $container->set(Logger::class, function (): Logger {
        $name = env('APP_NAME', 'App');

        $handlers = [
            new StreamHandler(
                logs_path(env('LOG_CHANNEL', 'app').'.log'),
                Level::fromName(env('LOG_LEVEL', 'Debug'))
            )
        ];

        $processors = [new PsrLogMessageProcessor(removeUsedContextFields: true)];
        $datetime_zone = new DateTimeZone(env('TIMEZONE', 'UTC'));

        return new Logger($name, $handlers, $processors, $datetime_zone);
    });

};
