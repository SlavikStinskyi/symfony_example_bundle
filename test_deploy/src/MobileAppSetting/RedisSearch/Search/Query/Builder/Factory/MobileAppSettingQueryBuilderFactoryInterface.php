<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Query\Builder\Factory;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Query\Builder\MobileAppSettingQueryBuilderInterface;

interface MobileAppSettingQueryBuilderFactoryInterface
{
    public function create(): MobileAppSettingQueryBuilderInterface;
}
