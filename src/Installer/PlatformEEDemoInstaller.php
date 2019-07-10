<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Installer;

use EzSystems\PlatformInstallerBundle\Installer\CoreInstaller;
use Symfony\Component\Filesystem\Filesystem;

class PlatformEEDemoInstaller extends CoreInstaller
{
    use InstallerCommandExecuteTrait;

    public function importData()
    {
        parent::importData();

        $liveSchema = $this->db->getSchemaManager()->createSchema();

        $dbPlatform = $this->db->getDatabasePlatform();
        foreach (['kaliop_migrations', 'kaliop_migrations_contexts'] as $tableName) {
            if (!$liveSchema->hasTable($tableName)) {
                continue;
            }

            $this->db->exec($dbPlatform->getTruncateTableSQL($tableName));
        }

        $migrationCommands = [
            'cache:clear',
            //'kaliop:migration:migrate --path=src/App/MigrationVersions/tags.yml -n',
            'kaliop:migration:migrate --path=src/MigrationVersions/languages.yml -v -n -u',
            'kaliop:migration:migrate --path=src/MigrationVersions/product_list.yml -n -u',
            'kaliop:migration:migrate --path=src/MigrationVersions/all.yml -n -u',
            'kaliop:migration:migrate --path=src/MigrationVersions/images.yml -v -n -u',
            'kaliop:migration:migrate --path=src/MigrationVersions/content.yml -v -n -u',
            'kaliop:migration:migrate --path=src/MigrationVersions/users.yml -v -n -u',
            'kaliop:migration:migrate --path=src/MigrationVersions/landing_page_contenttype.yml -n -u',
            'kaliop:migration:migrate --path=src/MigrationVersions/landing_page.yml -n -u',
            'kaliop:migration:migrate --path=src/MigrationVersions/form.yml -n -u',
            'kaliop:migration:migrate --path=src/MigrationVersions/cleanup-ee.yml -n -u',
        ];

        foreach ($migrationCommands as $cmd) {
            $this->output->writeln(sprintf('executing migration: %s', $cmd));
            $this->executeCommand($this->output, $cmd, 0);
        }
    }

    public function importBinaries()
    {
        $this->output->writeln('Copying storage directory contents...');
        $fs = new Filesystem();
        $fs->mkdir('web/var/site');
//        $fs->mirror(
//            'vendor/ezsystems/ezstudio-demo-bundle-data/data/storage',
//            'web/var/site/storage'
//        );
    }
}
