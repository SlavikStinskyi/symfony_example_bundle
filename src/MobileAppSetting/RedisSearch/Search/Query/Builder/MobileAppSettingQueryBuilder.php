<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Query\Builder;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Schema\MobileAppSettingIndexSchemaInterface;
use GinCms\Bundle\RedisSearchBundle\Index\Schema\IndexSchemaInterface;
use GinCms\Bundle\RedisSearchBundle\Search\Query\Builder\QueryBuilder;
use GinCms\Bundle\RedisSearchBundle\StringNormalizer\StringNormalizerInterface;

class MobileAppSettingQueryBuilder extends QueryBuilder implements MobileAppSettingQueryBuilderInterface
{
    public function __construct(
        private readonly IndexSchemaInterface $indexSchema,
        private readonly StringNormalizerInterface $stringNormalizer,
    ) {
        parent::__construct($this->indexSchema);
    }

    public function setName(string $name): MobileAppSettingQueryBuilderInterface
    {
        $name = $this->stringNormalizer->normalize($name);
        if ('' !== $name) {
            $this->setPhoneticStringEqualTo(
                $this->getMobileAppSettingIndexSchema()->getFieldName(),
                $name
            );
        }

        return $this;
    }

    public function setOperationSystem(string $operationSystem): MobileAppSettingQueryBuilderInterface
    {
        $operationSystem = $this->stringNormalizer->normalize($operationSystem);
        if ('' !== $operationSystem) {
            $this->setPhoneticStringEqualTo(
                $this->getMobileAppSettingIndexSchema()->getFieldOperationSystem(),
                $operationSystem
            );
        }

        return $this;
    }

    public function setEnabled(?bool $isEnabled): MobileAppSettingQueryBuilderInterface
    {
        if (null !== $isEnabled) {
            $this->setBoolEqualTo($this->getMobileAppSettingIndexSchema()->getFieldIsEnabled(), $isEnabled);
        }

        return $this;
    }

    /**
     * @return MobileAppSettingIndexSchemaInterface
     */
    private function getMobileAppSettingIndexSchema(): IndexSchemaInterface
    {
        return $this->getIndexSchema();
    }
}
