<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Query\Builder\Factory;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Query\Builder\MobileAppSettingQueryBuilder;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Query\Builder\MobileAppSettingQueryBuilderInterface;
use GinCms\Bundle\RedisSearchBundle\Index\Schema\IndexSchemaInterface;
use GinCms\Bundle\RedisSearchBundle\StringNormalizer\StringNormalizerInterface;

class MobileAppSettingQueryBuilderFactory implements MobileAppSettingQueryBuilderFactoryInterface
{
    public function __construct(
        private readonly StringNormalizerInterface $stringNormalizer,
        private readonly IndexSchemaInterface $indexSchema,
    ) {
    }

    public function create(): MobileAppSettingQueryBuilderInterface
    {
        return new MobileAppSettingQueryBuilder($this->indexSchema, $this->stringNormalizer);
    }
}
