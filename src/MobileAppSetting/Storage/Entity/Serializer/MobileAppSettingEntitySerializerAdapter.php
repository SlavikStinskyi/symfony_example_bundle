<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Storage\Entity\Serializer;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Mapper\MobileAppSettingDataMapperInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;
use GinCms\Bundle\StorageBundle\Entity\RedisStorageEntityInterface;
use GinCms\Bundle\StorageBundle\Entity\Serializer\RedisStorageEntitySerializerInterface;

class MobileAppSettingEntitySerializerAdapter implements RedisStorageEntitySerializerInterface
{
    public function __construct(
        private readonly MobileAppSettingDataMapperInterface $settingDataMapper,
    ) {
    }

    public function serialize(RedisStorageEntityInterface $entity): array
    {
        /** @var MobileAppSettingInterface $entity */
        return $this->settingDataMapper->toRaw($entity);
    }

    /**
     * @return MobileAppSettingInterface
     */
    public function unserialize(array $data): RedisStorageEntityInterface
    {
        return $this->settingDataMapper->toEntity($data);
    }
}
