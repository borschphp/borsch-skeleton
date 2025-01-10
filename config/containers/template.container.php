<?php

use App\Template\LatteEngine;
use Borsch\Template\TemplateRendererInterface;
use League\Container\Container;

return static function(Container $container): void {
    $container->add(TemplateRendererInterface::class, LatteEngine::class);
};
