<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Query\Builder;

use GinCms\Bundle\RedisSearchBundle\Search\Query\Builder\QueryBuilderInterface;

interface MobileAppSettingQueryBuilderInterface extends QueryBuilderInterface
{
    public function setName(string $name): self;

    public function setOperationSystem(string $operationSystem): self;

    public function setEnabled(?bool $isEnabled): self;
}
