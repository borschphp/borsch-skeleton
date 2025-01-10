<?php

use App\Listener\MonologListener;
use App\Middleware\ErrorHandlerMiddleware;
use Monolog\{Handler\StreamHandler, Level, Logger, Processor\PsrLogMessageProcessor};
use League\Container\{Container, ServiceProvider\AbstractServiceProvider};

return static function(Container $container): void {
    $container->addServiceProvider(new class extends AbstractServiceProvider {

        public function provides(string $id): bool
        {
            return in_array($id, [
                Logger::class,
                ErrorHandlerMiddleware::class
            ]);
        }

        public function register(): void
        {
            $this->getContainer()->add(Logger::class, function (): Logger {
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
            });

            $this
                ->getContainer()
                ->add(ErrorHandlerMiddleware::class)
                ->addMethodCall('addListener', [$this->getContainer()->get(MonologListener::class)]);
        }
    });
};
