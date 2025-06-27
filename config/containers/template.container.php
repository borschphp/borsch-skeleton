<?php

use Borsch\Latte\LatteRenderer;
use Borsch\Template\TemplateRendererInterface;
use League\Container\Container;

return static function(Container $container): void {
    $container
        ->add(
            TemplateRendererInterface::class,
            fn() => new LatteRenderer(
                storage_path('views'),
                cache_path('views'),
                !isProduction()
            )
        );
};
