<?php

namespace App\Package;

use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Script\Event;

class Installer
{

    private const INSTALL_MINIMAL = 'minimal';
    private const INSTALL_FULL = 'full';

    private string $project_root;
    private JsonFile $composer_json_file;
    /**
     * @var array{
     *     require: array<string, string>,
     *     require-dev: array<string, string>,
     *     ...
     * }
     */
    private array $composer_definition;
    private string $installation_type;

    public function __construct(private IOInterface $io)
    {
        $composer_file = Factory::getComposerFile();

        $this->project_root = realpath(dirname($composer_file));
        $this->project_root = rtrim($this->project_root, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

        $this->composer_json_file = new JsonFile($composer_file);
        $this->composer_definition = $this->composer_json_file->read();
    }

    public static function install(Event $event): void
    {
        $installer = new self($event->getIO());

        $installer->io->write('<info>Setting up optional packages</info>');

        $installer->installation_type = $installer->getInstallationType();
        $installer->setupApplication();
        $installer->cleanup();

        $installer->io->write('<info>Installation completed</info>');
    }

    private function getInstallationType(): string
    {
        $query = [
            "\n  <question>What type of application do you need ?</question>\n",
            "  [<comment>1</comment>] Minimal (to create API)\n",
            "  [<comment>2</comment>] Full    (with views)\n",
            '  Make your selection <comment>(2)</comment>: ',
        ];

        while (true) {
            $answer = $this->io->ask(implode('', $query), '2');

            switch ($answer) {
                case '1':
                    return self::INSTALL_MINIMAL;
                case '2':
                    return self::INSTALL_FULL;
                default:
                    $this->io->write('<error>Invalid answer</error>');
            }
        }
    }

    private function setupApplication(): void
    {
        if ($this->installation_type === self::INSTALL_MINIMAL) {
            $this->io->write('<info>Removing front handlers</info>');

            unlink($this->project_root.'src/Handler/HomeHandler.php');
            copy($this->project_root.'src/Package/Sources/routes.php', $this->project_root.'config/routes.php');

            $this->io->write('<info>Removing templates files and configuration</info>');
            unlink($this->project_root.'config/containers/template.container.php');
            copy($this->project_root.'src/Package/Sources/container.php', $this->project_root.'config/container.php');
            unlink($this->project_root.'storage/views/404.tpl');
            unlink($this->project_root.'storage/views/500.tpl');
            unlink($this->project_root.'storage/views/home.tpl');
            rmdir($this->project_root.'storage/views');

            $this->io->write('<info>Removing latte from composer.json</info>');
            unset($this->composer_definition['require']['borschphp/latte']);
            $this->composer_json_file->write($this->composer_definition);
        }
    }

    private function cleanup(): void
    {
        $this->io->write('<info>Cleaning up</info>');
        unset($this->composer_definition['require-dev']['composer/composer']);
        $this->composer_json_file->write($this->composer_definition);
        unlink($this->project_root.'src/Package/Installer.php');
        unlink($this->project_root.'src/Package/Sources/container.php');
        rmdir($this->project_root.'src/Package/Sources');
        rmdir($this->project_root.'src/Package');
    }
}
