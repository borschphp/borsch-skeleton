<?php

use App\Handler\PeoplesHandler;
use App\Repository\PeopleRepositoryInterface;
use League\Container\{Container, ServiceProvider\AbstractServiceProvider};

return static function(Container $container): void {
    $container->addServiceProvider(new class extends AbstractServiceProvider {

        public function provides(string $id): bool
        {
            return in_array($id, [
                PeoplesHandler::class
            ]);
        }

        public function register(): void
        {
            $this
                ->getContainer()
                ->add(PeoplesHandler::class)
                ->addArgument(PDO::class)
                ->addArgument(PeopleRepositoryInterface::class);
        }
    });
};
