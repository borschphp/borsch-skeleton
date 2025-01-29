<?php

namespace App\Package;

use Composer\Composer;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Package\Link;
use Composer\Package\RootPackageInterface;
use Composer\Script\Event;

class Installer
{

    private const INSTALL_MINIMAL = 'minimal';
    private const INSTALL_FULL = 'full';

    private string $project_root;
    private JsonFile $composer_json_file;
    private array $composer_definition;
    private RootPackageInterface $composer_root_package;
    /** @var Link[] */
    private array $composer_root_requires;
    /** @var Link[] */
    private array $composer_root_dev_requires;

    private string $installation_type;

    public function __construct(private IOInterface $io, private Composer $composer)
    {
        $composer_file = Factory::getComposerFile();

        $this->project_root = $project_root ?? realpath(dirname($composer_file));
        $this->project_root = rtrim($this->project_root, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

        $this->composer_json_file = new JsonFile($composer_file);
        $this->composer_definition = $this->composer_json_file->read();
        $this->composer_root_package = $this->composer->getPackage();
        $this->composer_root_requires = $this->composer_root_package->getRequires();
        $this->composer_root_dev_requires = $this->composer_root_package->getDevRequires();
    }

    public static function install(Event $event): void
    {
        $installer = new self($event->getIO(), $event->getComposer());

        $installer->io->write('<info>Setting up optional packages</info>');

        $installer->installation_type = $installer->getInstallationType();
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
}
