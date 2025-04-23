<?php

use League\Container\{Container, ServiceProvider\AbstractServiceProvider};

return static function(Container $container): void {
    $container->addServiceProvider(new class extends AbstractServiceProvider {

        public function provides(string $id): bool
        {
            return in_array($id, [
                PDO::class
            ]);
        }

        public function register(): void
        {
            $this
                ->getContainer()
                ->add(PDO::class)
                ->addArgument('sqlite:'.storage_path('database.sqlite'))
                ->addMethodCall('setAttribute', [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION])
                ->addMethodCall('setAttribute', [PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC])
                ->addMethodCall('setAttribute', [PDO::ATTR_EMULATE_PREPARES, false]);
        }
    });
};
