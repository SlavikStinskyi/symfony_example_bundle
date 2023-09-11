<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Storage\Entity\Serializer;

use Ds\Set;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Factory\MobileAppSettingFactoryInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSetting;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;
use GinCms\Bundle\StorageBundle\Entity\RedisStorageEntityInterface;
use GinCms\Bundle\StorageBundle\Entity\Serializer\RedisStorageEntitySerializerInterface;

class MobileAppSettingEntitySerializer implements RedisStorageEntitySerializerInterface
{
    /**
     * Constants represent parameters related to way of storing an entity.
     */
    private const FIELD_VERSION = 'version';

    private const FIELD_ID = 'id';

    private const FIELD_NAME = 'name';

    private const FIELD_OPERATION_SYSTEM = 'operation_system';

    private const FIELD_DEEPLINK = 'deeplink';

    private const FIELD_BANNED_REF_CODES = 'banned_ref_codes';

    private const FIELD_IS_ENABLED = 'is_enabled';

    private const FIELD_RATE = 'app_rate';

    private const FIELD_UPDATED_AT = 'updatet_at';

    public function __construct(
        private readonly MobileAppSettingFactoryInterface $appSettingFactory,
    ) {
    }

    public function serialize(RedisStorageEntityInterface $entity): array
    {
        if (false === $entity instanceof MobileAppSettingInterface) {
            throw new \DomainException(
                \sprintf('Wrong entity type given %s, expected %s', $entity::class, MobileAppSetting::class)
            );
        }

        return [
            self::FIELD_VERSION => $entity->getVersion(),
            self::FIELD_ID => $entity->getId(),
            self::FIELD_NAME => $entity->getName(),
            self::FIELD_OPERATION_SYSTEM => $entity->getOperationSystem(),
            self::FIELD_DEEPLINK => $entity->getDeeplink(),
            self::FIELD_BANNED_REF_CODES => \json_encode(
                $entity->getBannedRefCodes()->toArray(),
                \JSON_THROW_ON_ERROR
            ),
            self::FIELD_IS_ENABLED => $entity->isEnabled(),
            self::FIELD_RATE => $entity->getAppRate(),
            self::FIELD_UPDATED_AT => (new \DateTimeImmutable())->format(\DateTimeInterface::W3C),
        ];
    }

    public function unserialize(array $data): RedisStorageEntityInterface
    {
        if (false === \array_key_exists(self::FIELD_VERSION, $data)
            || false === \array_key_exists(self::FIELD_ID, $data)
            || false === \array_key_exists(self::FIELD_NAME, $data)
            || false === \array_key_exists(self::FIELD_OPERATION_SYSTEM, $data)
            || false === \array_key_exists(self::FIELD_DEEPLINK, $data)
        ) {
            throw new \InvalidArgumentException('MobileAppSetting badly formed');
        }

        $updatedAt = null;
        if (\array_key_exists(self::FIELD_UPDATED_AT, $data)) {
            $updatedAt = new \DateTimeImmutable($data[self::FIELD_UPDATED_AT]);
        }

        $bannedRefCodes = new Set(
            \json_decode($data[self::FIELD_BANNED_REF_CODES], true, 512, \JSON_THROW_ON_ERROR)
        );

        return $this->appSettingFactory->create(
            $data[self::FIELD_VERSION],
            $data[self::FIELD_ID],
            $data[self::FIELD_NAME],
            $data[self::FIELD_OPERATION_SYSTEM],
            $data[self::FIELD_DEEPLINK],
            $bannedRefCodes,
            $updatedAt,
            (bool) $data[self::FIELD_IS_ENABLED],
            (float) $data[self::FIELD_RATE],
        );
    }
}
