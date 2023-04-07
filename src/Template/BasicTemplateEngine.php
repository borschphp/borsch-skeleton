<?php

namespace App\Template;

use InvalidArgumentException;

/**
 * Class BasicTemplateEngine
 * @package App\Template
 */
class BasicTemplateEngine
{

    protected array $template_dirs = [];

    protected string $cache_dir;

    protected bool $use_cache = false;

    protected array $parameters = [];

    public function setTemplateDir(string|array $dirs): static
    {
        foreach ((array)$dirs as $dir) {
            if (!is_string($dir) || !is_dir($dir)) {
                throw new InvalidArgumentException(
                    'Template directory must be a valid path.'
                );
            }
        }

        $this->template_dirs = array_map(
            fn ($path) => rtrim($path, ' /').'/',
            (array)$dirs
        );

        return $this;
    }

    public function setCacheDir(string $dir): static
    {
        if (!is_dir($dir)) {
            throw new InvalidArgumentException(
                'Cache directory must be a valid path.'
            );
        }

        $this->cache_dir = rtrim($dir, ' /').'/';

        return $this;
    }

    public function useCache(bool $yesno): static
    {
        $this->use_cache = $yesno;

        return $this;
    }

    public function assign(string|array $name, $value = null): void
    {
        if (is_string($name)) {
            $name = [$name => $value];
        }

        $this->parameters = array_merge($this->parameters, $name);
    }

    public function render(string $template, array $context = []): string
    {
        foreach ($this->template_dirs as $template_dir) {
            $tpl_file = $template_dir.$template;

            if (file_exists($tpl_file)) {
                $cache_file = $this->cache_dir.$template.'.php';

                if (!$this->use_cache || !file_exists($cache_file)) {
                    $code = file_get_contents($tpl_file);

                    $code = preg_replace(
                        [
                            '~\{\s*\*.+?\*\s*}~',  // comments
                            '~\{%\s*(.+?)\s*}~',   // commands (foreach(): endforeach;)
                            '~\{!!\s*(.+?)\s*}~',  // unescaped vars
                            '~\{\s*(.+?)\s*}~'     // escaped vars (default)
                        ],
                        [
                            '',
                            '<?php $1 ?>',
                            '<?php echo $1 ?>',
                            '<?php echo htmlspecialchars($1, ENT_QUOTES) ?>'
                        ],
                        $code
                    );

                    file_put_contents($cache_file, $code);
                }

                extract($this->parameters);

                ob_start();
                include $cache_file;
                return ob_get_clean();
            }
        }

        throw new InvalidArgumentException(sprintf(
            'Unable to find template "%s".',
            $template
        ));
    }

}
