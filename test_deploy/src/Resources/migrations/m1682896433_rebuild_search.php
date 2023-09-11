<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\Resources\migrations;

use GinCms\Bundle\MigrationsBundle\Migration;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Schema\MobileAppSettingIndexSchemaInterface;
use GinCms\Bundle\RedisSearchBundle\Index\RediSearch\Manager\RediSearchIndexManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class m1682896433_rebuild_search extends Migration
{
    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly RediSearchIndexManagerInterface $rediSearchIndexManager,
        private readonly MobileAppSettingIndexSchemaInterface $indexSchema,
        private readonly string $entityName,
    ) {
    }

    public function up(): void
    {
        $status = $this->createIndex();

        if (false === $status) {
            throw new \DomainException('cannot create mobile_app_setting index');
        }

        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(
            [
                'command' => 'redis-search:rebuild-index',
                'name' => $this->entityName,
                '--force-rebuild-schema' => 1,
            ]
        );

        $output = new BufferedOutput();
        $code = $application->run($input, $output);

        if (0 !== $code) {
            throw new \DomainException('Index rebuild error: ' . $output->fetch());
        }
    }

    private function createIndex(): bool
    {
        return $this->rediSearchIndexManager->createIndex(
            $this->indexSchema
        );
    }
}
