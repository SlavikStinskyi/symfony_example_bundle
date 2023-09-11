<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Mapper;

use Ds\Set;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Enum\OperationSystemEnum;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Factory\MobileAppSettingFactoryInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;

class MobileAppSettingDataMapper implements MobileAppSettingDataMapperInterface
{
    private const MISSING_FIELD_ERROR = 'Field %s is missing';

    /**
     * Constants represent parameters related to way of storing an entity.
     */
    private const FIELD_VERSION = 'version';

    private const FIELD_ID = 'id';

    private const FIELD_NAME = 'name';

    private const FIELD_OPERATION_SYSTEM = 'operation_system';

    private const FIELD_APP_LINK = 'app_link';

    private const FIELD_DEEPLINK = 'deeplink';

    private const FIELD_BANNED_REF_CODES = 'banned_ref_codes';

    private const FIELD_IS_ENABLED = 'is_enabled';

    private const FIELD_RATE = 'app_rate';

    private const FIELD_UPDATED_AT = 'updated_at';

    public function __construct(
        private readonly MobileAppSettingFactoryInterface $appSettingFactory,
        private readonly bool $encodeRefCodes,
    ) {
    }

    public function toRaw(MobileAppSettingInterface $mobileAppSetting): array
    {
        $bannedRefCodes = true === $this->encodeRefCodes
            ? \json_encode($mobileAppSetting->getBannedRefCodes()->toArray(), \JSON_THROW_ON_ERROR)
            : $mobileAppSetting->getBannedRefCodes()->toArray();

        return [
            self::FIELD_VERSION => $mobileAppSetting->getVersion(),
            self::FIELD_ID => $mobileAppSetting->getId(),
            self::FIELD_NAME => $mobileAppSetting->getName(),
            self::FIELD_OPERATION_SYSTEM => $mobileAppSetting->getOperationSystem()->value,
            self::FIELD_APP_LINK => $mobileAppSetting->getAppLink(),
            self::FIELD_DEEPLINK => $mobileAppSetting->getDeeplink(),
            self::FIELD_BANNED_REF_CODES => $bannedRefCodes,
            self::FIELD_IS_ENABLED => $mobileAppSetting->isEnabled(),
            self::FIELD_RATE => $mobileAppSetting->getAppRate(),
            self::FIELD_UPDATED_AT => (new \DateTimeImmutable())->format(\DateTimeInterface::W3C),
        ];
    }

    public function toEntity(array $rawData): MobileAppSettingInterface
    {
        $updatedAt = null;
        if (\array_key_exists(self::FIELD_UPDATED_AT, $rawData)) {
            $updatedAt = new \DateTimeImmutable($rawData[self::FIELD_UPDATED_AT]);
        }

        $bannedRefCodes = $this->encodeRefCodes
            ? \json_decode($rawData[self::FIELD_BANNED_REF_CODES], true, 512, \JSON_THROW_ON_ERROR)
            : $rawData[self::FIELD_BANNED_REF_CODES];

        return $this->appSettingFactory->create(
            $this->getByKey(self::FIELD_VERSION, $rawData),
            $this->getByKey(self::FIELD_ID, $rawData),
            $this->getByKey(self::FIELD_NAME, $rawData),
            $this->extractOperationSystem($rawData),
            $this->getByKey(self::FIELD_APP_LINK, $rawData),
            $this->getByKey(self::FIELD_DEEPLINK, $rawData),
            new Set($bannedRefCodes),
            $updatedAt,
            (bool) $rawData[self::FIELD_IS_ENABLED],
            (float) $rawData[self::FIELD_RATE],
        );
    }

    private function getByKey(string $key, array $rawData): mixed
    {
        if (false === \array_key_exists($key, $rawData)) {
            throw new \InvalidArgumentException(\sprintf(self::MISSING_FIELD_ERROR, $key));
        }

        return $rawData[$key];
    }

    private function extractOperationSystem(array $rawData): OperationSystemEnum
    {
        return OperationSystemEnum::from(
            $this->getByKey(self::FIELD_OPERATION_SYSTEM, $rawData)
        );
    }
}
