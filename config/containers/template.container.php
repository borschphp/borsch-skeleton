<?php

use App\Template\LatteEngine;
use Borsch\Container\Container;
use Borsch\Template\TemplateRendererInterface;

return static function(Container $container): void {
    $container
        ->set(TemplateRendererInterface::class, LatteEngine::class)
        ->cache(true);
};
