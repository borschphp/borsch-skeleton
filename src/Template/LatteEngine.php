<?php

namespace App\Template;

use Borsch\Template\AbstractTemplateRenderer;
use InvalidArgumentException;
use Latte\Engine;
use Latte\Loaders\FileLoader;

class LatteEngine extends AbstractTemplateRenderer
{

    protected Engine $latte;

    public function __construct()
    {
        $this->latte = new Engine();
        $this->latte->setTempDirectory(cache_path('views'));
        $this->latte->setLoader(new FileLoader(storage_path('views')));
        $this->latte->setAutoRefresh(!isProduction());
    }

    /**
     * @param string $name
     * @param array<string, mixed> $params
     * @return string
     */
    public function render(string $name, array $params = []): string
    {
        $namespace_template = explode('::', $name);
        $count = count($namespace_template);

        if ($count == 0 || $count > 2) {
            throw new InvalidArgumentException('Invalide `namespace::template` parameter provided...');
        }

        $template = $namespace_template[0];
        if ($count == 2) {
            $template = sprintf('%s/%s', $template, $namespace_template[1]);
        }

        $template = trim($template);
        if (!str_ends_with($template, '.tpl')) {
            $template .= '.tpl';
        }

        $params = array_merge($this->parameters, $params);

        // Clone Latte so $params will not override the one already in place.
        $latte = clone $this->latte;

        return $latte->renderToString($template, $params);
    }
}
