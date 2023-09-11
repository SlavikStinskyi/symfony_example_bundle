<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Factory;

use Ds\Set;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Enum\OperationSystemEnum;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;

interface MobileAppSettingFactoryInterface
{
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
        float $appRate,
    ): MobileAppSettingInterface;

    public function createNew(
        string $id,
        string $name,
        OperationSystemEnum $operationSystem,
        string $appLink,
        string $deeplink,
        Set $bannedRefCodes,
        bool $isEnabled,
        float $appRate,
    ): MobileAppSettingInterface;
}
