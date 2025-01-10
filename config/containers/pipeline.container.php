<?php

use App\Listener\MonologListener;
use Borsch\Middleware\ErrorHandlerMiddleware;
use League\Container\{Container, ServiceProvider\AbstractServiceProvider};

return static function(Container $container): void {
    $container->addServiceProvider(new class extends AbstractServiceProvider {

        public function provides(string $id): bool
        {
            return in_array($id, [
                ErrorHandlerMiddleware::class
            ]);
        }

        public function register(): void
        {
            $this
                ->getContainer()
                ->add(ErrorHandlerMiddleware::class)
                ->addMethodCall('addListener', [$this->getContainer()->get(MonologListener::class)]);
        }
    });
};
