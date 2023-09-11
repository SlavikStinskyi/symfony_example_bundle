<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Service;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Service\Result\MobileAppSettingSearchServiceResultInterface;

/** @internal  */
interface MobileAppSettingSearchServiceInterface
{
    public function findBy(
        string $operationSystem = '',
        string $name = '',
        int $limit = 0,
        int $offset = 0,
        ?bool $isEnabled = null,
    ): MobileAppSettingSearchServiceResultInterface;
}
