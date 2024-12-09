<?php

namespace App;

use Composer\Composer;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Package\Link;
use Composer\Script\Event;
use RuntimeException;

class Installer
{

    const INSTALL_API = 'api';
    const INSTALL_VIEWS = 'views';

    protected string $composer_file;
    protected array $composer_definitions;
    /** @var Link[] */
    protected array $composer_requires;
    /** @var Link[] */
    protected array $composer_dev_requires;
    protected string $root_dir;

    public function __construct(protected IOInterface $io, protected Composer $composer)
    {
        $this->composer_file = Factory::getComposerFile();
        $this->root_dir = rtrim(realpath(dirname($this->composer_file)), '/\\').'/';

        $this->getComposerDefinition();
    }

    public static function configure(Event $event): void
    {
        $configurator = new self($event->getIO(), $event->getComposer());

        $configurator->io->write('<info>Configuring the application</info>');

        $installation_type = $configurator->getInstallationType();
        $configurator->setupApplication($installation_type);
        // TODO other options
        // TODO ask for database configuration (cache)
        // TODO rebuild the composer.json file and update the autoloader
    }

    protected function getComposerDefinition(): void
    {
        $json = new JsonFile($this->composer_file);
        $this->composer_definitions = $json->read();

        $this->composer_requires = $this->composer->getPackage()->getRequires();
        $this->composer_dev_requires = $this->composer->getPackage()->getDevRequires();
    }

    protected function getInstallationType(): string
    {
        $query = [
            sprintf(
                "\n  <question>%s</question>\n",
                'What type of installation would you like?'
            ),
            "  [<comment>1</comment>] API only (without views)\n",
            "  [<comment>2</comment>] Views needed (with added packages to manage front-end)\n",
            '  Make your selection <comment>(1)</comment>: ',
        ];

        while (true) {
            $answer = $this->io->ask(implode('', $query), '1');

            switch ($answer) {
                case '1':
                    return self::INSTALL_API;
                case '2':
                    return self::INSTALL_VIEWS;
                default:
                    $this->io->write('  <error>Invalid answer, choose between 1 or 2</error>');
            }
        }
    }

    protected function setupApplication(string $installation_type): void
    {
        switch ($installation_type) {
            case self::INSTALL_API:
                $this->setupApi();
                break;
            case self::INSTALL_VIEWS:
                // For now, nothing to do, views are already included
                // Propose to switch to twig ? smarty ? ...
                break;
            default:
                throw new RuntimeException('Invalid installation type');
        }
    }

    protected function setupApi(): void
    {
        $this->io->write('<info>Removing views packages from composer.json</info>');
        unset($this->composer_requires['latte/latte']);
        unset($this->composer_requires['borschphp/template']);

        $this->io->write('<info>Removing template definition from container</info>');
        unlink($this->root_dir.'config/containers/template.container.php');

        $this->io->write('<info>Removing template classes</info>');
        unlink($this->root_dir.'src/Template/LatteEngine.php');
        rmdir($this->root_dir.'src/Template');

        $this->io->write('<info>Removing handlers using template classes</info>');
        unlink($this->root_dir.'src/Handler/HomeHandler.php');
        // TODO clean config/routes.php (or remove this file and clean index.php and worker.php)

        $this->io->write('<info>Removing views directory and files</info>');
        unlink($this->root_dir.'storage/views/404.tpl');
        unlink($this->root_dir.'storage/views/500.tpl');
        unlink($this->root_dir.'storage/views/home.tpl');
        rmdir($this->root_dir.'storage/views');

        $this->io->write('<info>Removing views from NotFoundMiddleware</info>');
        // TODO clean src/Middleware/NotFoundMiddleware.php or use some kind of config to load views or not
    }
}
