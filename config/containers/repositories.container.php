<?php

use Borsch\Router\RouterInterface;
use League\Container\{Container, ServiceProvider\AbstractServiceProvider};

return static function(Container $container): void {
    $container->addServiceProvider(new class extends AbstractServiceProvider {

        public function provides(string $id): bool
        {
            return in_array($id, []);
        }

        public function register(): void
        {
        }
    });
};
