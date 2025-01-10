<?php

use Borsch\Router\RouterInterface;
use League\Container\{Container, ServiceProvider\AbstractServiceProvider};
use App\Repository\{PeopleRepositoryInterface, SQLitePeopleRepository};

return static function(Container $container): void {
    $container->addServiceProvider(new class extends AbstractServiceProvider {

        public function provides(string $id): bool
        {
            return in_array($id, [
                PeopleRepositoryInterface::class
            ]);
        }

        public function register(): void
        {
            $this
                ->getContainer()
                ->add(PeopleRepositoryInterface::class, SQLitePeopleRepository::class)
                ->addArgument(PDO::class)
                ->addArgument(RouterInterface::class);
        }
    });
};
