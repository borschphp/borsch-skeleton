<?php

use Borsch\Container\Container;
use Borsch\Latte\LatteRenderer;
use Borsch\Template\TemplateRendererInterface;

return static function (Container $container) {

    $container->set(
        TemplateRendererInterface::class,
        fn() => new LatteRenderer(storage_path('views'), cache_path('views'), !isProduction())
    );

};
