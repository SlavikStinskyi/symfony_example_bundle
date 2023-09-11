<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Factory;

use Ds\Set;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Enum\OperationSystemEnum;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Generator\MobileAppSettingIdGenerator;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSetting;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;

class MobileAppSettingFactory implements MobileAppSettingFactoryInterface
{
    public function __construct(
        private readonly MobileAppSettingIdGenerator $idGenerator,
    ) {
    }

    public function create(
        string $version,
        string $id,
        string $name,
        OperationSystemEnum $operationSystem,
        string $appLink,
        string $deeplink,
        Set $bannedRefCodes,
        \DateTimeImmutable $updatedAt,
        bool $isEnabled,
        ?float $appRate = null,
    ): MobileAppSettingInterface {
        return new MobileAppSetting(
            $version,
            $id,
            $name,
            $operationSystem,
            $appLink,
            $deeplink,
            $bannedRefCodes,
            $updatedAt,
            $isEnabled,
            $appRate,
        );
    }

    public function createNew(
        string $id,
        string $name,
        OperationSystemEnum $operationSystem,
        string $appLink,
        string $deeplink,
        Set $bannedRefCodes,
        bool $isEnabled,
        float $appRate,
    ): MobileAppSettingInterface {
        return $this->create(
            $this->idGenerator->generateId(),
            $id,
            $name,
            $operationSystem,
            $appLink,
            $deeplink,
            $bannedRefCodes,
            new \DateTimeImmutable(),
            $isEnabled,
            $appRate,
        );
    }
}
