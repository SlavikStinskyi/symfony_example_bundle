<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Service\Result\Factory;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Service\Result\MobileAppSettingSearchServiceResult;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Service\Result\MobileAppSettingSearchServiceResultInterface;

class MobileAppSettingSearchServiceResultFactory implements MobileAppSettingSearchServiceResultFactoryInterface
{
    public function create(
        array $mobileAppSettings,
        int $count,
    ): MobileAppSettingSearchServiceResultInterface {
        return new MobileAppSettingSearchServiceResult(
            $mobileAppSettings,
            $count
        );
    }
}
