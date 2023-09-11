<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\RebuildIndex\Provider;

use Ds\Set;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Schema\MobileAppSettingIndexSchemaInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Storage\MobileAppSettingStorageInterface;
use GinCms\Bundle\RedisSearchBundle\Index\Document\IndexDocumentInterface;
use GinCms\Bundle\RedisSearchBundle\Service\RebuildIndex\Provider\SearchRebuildIndexServiceDataProviderInterface;

class MobileAppSettingSearchRebuildIndexServiceDataProvider implements SearchRebuildIndexServiceDataProviderInterface
{
    public function __construct(
        private MobileAppSettingIndexSchemaInterface $schema,
        private MobileAppSettingStorageInterface $storage,
        private string $entityType,
    ) {
    }

    public function getDocuments(array $ids): Set
    {
        $entities = $this->storage->getByIds($ids);

        /** @var Set<IndexDocumentInterface> $documents */
        $documents = new Set();
        foreach ($entities as $entity) {
            $document = $this->getSchema()->extractDocument($entity);
            $documents->add($document);
        }

        return $documents;
    }

    public function getSchema(): MobileAppSettingIndexSchemaInterface
    {
        return $this->schema;
    }

    public function getIds(): array
    {
        return $this->storage->getAllIds();
    }

    public function getEntityType(): string
    {
        return $this->entityType;
    }

    public function getName(): string
    {
        return $this->entityType;
    }
}
