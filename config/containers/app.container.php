<?php

use Borsch\{Application\Application,
    Application\ApplicationInterface,
    RequestHandler\RequestHandler,
    RequestHandler\RequestHandlerInterface,
    Router\FastRouteRouter,
    Router\RouterInterface};
use Laminas\Diactoros\ServerRequestFactory;
use League\Container\{Container, ServiceProvider\AbstractServiceProvider};
use Psr\Http\Message\ServerRequestInterface;

return static function(Container $container): void {
    $container->addServiceProvider(new class extends AbstractServiceProvider {

        public function provides(string $id): bool
        {
            return in_array($id, [
                ApplicationInterface::class,
                RouterInterface::class,
                RequestHandlerInterface::class,
                ServerRequestInterface::class
            ]);
        }

        public function register(): void
        {
            $this
                ->getContainer()
                ->add(ApplicationInterface::class, Application::class)
                ->addArgument(RequestHandlerInterface::class)
                ->addArgument(RouterInterface::class)
                ->addArgument($this->getContainer());

            $this
                ->getContainer()
                ->add(RouterInterface::class, function () {
                    $router = new FastRouteRouter();
                    if (isProduction()) {
                        $router->setCacheFile(cache_path('routes.cache.php'));
                    }

                    return $router;
                });

            $this
                ->getContainer()
                ->add(RequestHandlerInterface::class, RequestHandler::class);

            $this
                ->getContainer()
                ->add(ServerRequestInterface::class, fn() => ServerRequestFactory::fromGlobals())
                ->setShared(false);
        }
    });
};
