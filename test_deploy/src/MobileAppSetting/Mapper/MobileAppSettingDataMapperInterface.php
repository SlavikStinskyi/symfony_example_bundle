<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Mapper;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;

interface MobileAppSettingDataMapperInterface
{
    public function toRaw(MobileAppSettingInterface $mobileAppSetting): array;

    public function toEntity(array $rawData): MobileAppSettingInterface;
}
