<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Storage;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;
use GinCms\Bundle\StorageBundle\RedisStorageInterface;

/**
 * @template-extends RedisStorageInterface<MobileAppSettingInterface>
 */
interface MobileAppSettingStorageInterface extends RedisStorageInterface
{
}
