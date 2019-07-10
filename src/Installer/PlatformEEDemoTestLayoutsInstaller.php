<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Installer;

use EzSystems\PlatformInstallerBundle\Installer\CleanInstaller;
use Symfony\Component\Filesystem\Filesystem;

class PlatformEEDemoTestLayoutsInstaller extends CleanInstaller
{
    use InstallerCommandExecuteTrait;

    public function importSchema()
    {
    }

    public function importData()
    {
        $migrationCommands = [
            'cache:clear',
            'kaliop:migration:migrate --path=src/App/MigrationVersions/landing_page_tests.yml -n',
        ];

        foreach ($migrationCommands as $cmd) {
            $this->output->writeln(sprintf('executing migration: %s', $cmd));
            $this->executeCommand($this->output, $cmd, 0);
        }
    }

    public function importBinaries()
    {
    }
}
