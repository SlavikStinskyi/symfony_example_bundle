<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Service;

use Ds\Set;
use GameInspire\Error\Factory\ErrorFactoryInterface;
use GameInspire\Result\Factory\ResultFactoryInterface;
use GameInspire\Result\ResultInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Mapper\MobileAppSettingDataMapperInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Schema\MobileAppSettingIndexSchemaInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Storage\MobileAppSettingStorageInterface;
use GinCms\Bundle\RedisSearchBundle\Index\IndexServiceInterface;
use GinCms\Bundle\StorageBundle\EntityModificationStorageInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Factory\MobileAppSettingFactoryInterface;


class MobileAppSettingService implements MobileAppSettingServiceInterface
{
    public function __construct(
        private readonly MobileAppSettingStorageInterface $appSettingStorage,
        private readonly EntityModificationStorageInterface $modificationStorage,
        private readonly IndexServiceInterface $indexService,
        private readonly MobileAppSettingIndexSchemaInterface $indexSchema,
        private readonly ResultFactoryInterface $resultFactory,
        private readonly ErrorFactoryInterface $errorFactory,
        private readonly MobileAppSettingDataMapperInterface $appSettingDataMapper,
        private readonly MobileAppSettingFactoryInterface $mobileAppSettingFactory,
    ) {
    }

    public function getById(string $id): ?MobileAppSettingInterface
    {
        return $this->appSettingStorage->getById($id);
    }

    /**
     * @param string[] $ids
     *
     * @return \Traversable<MobileAppSettingInterface>
     */
    public function getByIds(array $ids): \Traversable
    {
        return $this->appSettingStorage->getByIds($ids);
    }

    public function getAll(): \Traversable
    {
        return $this->appSettingStorage->getAll();
    }

    public function create(MobileAppSettingInterface $appSetting): ResultInterface
    {
        $result = $this->modificationStorage->create($appSetting);
        if (null !== $result->getError()) {
            return $result;
        }

        return $this->indexService
            ->add(new Set([$this->indexSchema->extractDocument($appSetting)]));
    }

    public function replace(
        MobileAppSettingInterface $currentEntity,
        MobileAppSettingInterface $newEntity,
    ): ResultInterface {
        $result = $this->modificationStorage->replace($currentEntity, $newEntity);
        if (null !== $result->getError()) {
            return $result;
        }

        $replaced = $this->indexService
            ->replace(
                $this->indexSchema->extractDocument($currentEntity),
                $this->indexSchema->extractDocument($newEntity)
            );

        if (false === $replaced) {
            return $this->resultFactory->createWithError(
                $this->errorFactory->createError(
                    'Failed to update mobile_app_setting index',
                    [
                        'current_entity' => $this->appSettingDataMapper->toRaw($currentEntity),
                        'new_entity' => $this->appSettingDataMapper->toRaw($newEntity),
                    ]
                )
            );
        }

        return $this->resultFactory->createSuccessful();
    }

    public function remove(MobileAppSettingInterface $appSetting): ResultInterface
    {
        $result = $this->modificationStorage->remove($appSetting);
        if (null !== $result->getError()) {
            return $result;
        }

        return $this->indexService
            ->remove(new Set([$this->indexSchema->extractDocument($appSetting)]));
    }

    public function copy(MobileAppSettingInterface $appSetting): ResultInterface
    {
        $mobileSetting = $this->mobileAppSettingFactory->createNew(
            id: '',
            name: $this->generateConfigName($appSetting),
            operationSystem: $appSetting->getOperationSystem(),
            deeplink: $appSetting->getDeeplink(),
            bannedRefCodes: $appSetting->getBannedRefCodes(),
            isEnabled: false,
            appRate: $appSetting->getAppRate(),
            appLink: $appSetting->getAppLink()
        );

        return $this->create($mobileSetting);
        return $this->indexService
            ->add(new Set([$this->indexSchema->extractDocument($this->create($mobileSetting))]));
    }

    private function generateConfigName(MobileAppSettingInterface $appSetting): string
    {
        $namePrefix = 'copy_of';
        return \sprintf(
            '%s_%s',
            $namePrefix,
            $appSetting->getName()
        );
    }
}
