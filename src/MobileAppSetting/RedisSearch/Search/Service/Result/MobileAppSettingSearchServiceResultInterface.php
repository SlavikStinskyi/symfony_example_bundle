<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Service\Result;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;

/** @internal  */
interface MobileAppSettingSearchServiceResultInterface extends \JsonSerializable
{
    public const FIELD_MOBILE_APP_SETTINGS = 'mobile_app_settings';

    public const FIELD_COUNT = 'count';

    /**
     * @return MobileAppSettingInterface[]
     */
    public function getMobileAppSettings(): array;

    public function getCount(): int;
}
