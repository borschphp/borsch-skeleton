<?php

namespace App\Package;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Script\Event;

class Installer
{

    private const INSTALL_MINIMAL = 'minimal';
    private const INSTALL_FULL = 'full';

    private string $installation_type;

    public function __construct(
        private IOInterface $io,
        private Composer $composer,
        ?string $projectRoot = null
    ) {}

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
