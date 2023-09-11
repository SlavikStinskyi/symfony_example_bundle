<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting;

use Ds\Set;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Enum\OperationSystemEnum;
use GinCms\Bundle\StorageBundle\Entity\RedisStorageEntityInterface;

interface MobileAppSettingInterface extends RedisStorageEntityInterface
{
    public function getName(): string;

    public function getOperationSystem(): OperationSystemEnum;

    public function getAppRate(): ?float;

    public function getAppLink(): string;

    public function getDeeplink(): string;

    public function isEnabled(): bool;

    public function getBannedRefCodes(): Set;
}
