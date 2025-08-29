<?php

use Borsch\Container\Container;
use Borsch\Latte\LatteRenderer;
use Borsch\Template\TemplateRendererInterface;

return static function (Container $container) {

    /*
     * A Template renderer is used to render views.
     * This one uses Latte as the template engine.
     * It is configured to use the 'storage/views' directory for templates and `storage/cache/views` for caching.
     */
    $container->set(
        TemplateRendererInterface::class,
        fn() => new LatteRenderer(storage_path('views'), cache_path('views'), !isProduction())
    );

};
