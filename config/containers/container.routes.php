<?php

use Borsch\Container\Container;
use Borsch\Router\Contract\{RouteInterface, RouterInterface};
use Borsch\Router\FastRouteRouter;
use Borsch\Router\Loader\AttributeRouteLoader;
use Psr\Container\ContainerInterface;

return static function (Container $container) {

    /*
     * Load routes from attributes in controllers.
     *
     * The routes will be cached in the specified file when in production environment.
     * In development, the routes will be loaded from the source files directly.
     */
    $container->set(AttributeRouteLoader::class, static function (ContainerInterface $container) {
        $loader = new AttributeRouteLoader(
            [__ROOT_DIR__ . '/src/Application'],
            $container,
            cache_path('loader.routes.cache.php'),
            !isProduction()
        );

        return $loader->load();
    });

    /*
     * Register the router service.
     *
     * In production, it uses a cached version of the routes for performance.
     * In development, it loads routes directly from the source files.
     */
    $container->set(RouterInterface::class, static function (AttributeRouteLoader $loader) {
        $routes = $loader->getRoutes();

        if (isProduction()) {
            return new FastRouteRouter(
                array_combine(
                    array_map(fn(RouteInterface $route) => $route->getName(), $routes),
                    $routes
                ),
                cache_path('router.routes.cache.php')
            );
        }

        $router = new FastRouteRouter();
        foreach ($routes as $route) {
            $router->addRoute($route);
        }

        return $router;
    });

};
