<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Service;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Query\Builder\Factory\MobileAppSettingQueryBuilderFactoryInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Query\Builder\MobileAppSettingQueryBuilderInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Service\Result\Factory\MobileAppSettingSearchServiceResultFactoryInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Service\Result\MobileAppSettingSearchServiceResultInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Storage\MobileAppSettingStorageInterface;
use GinCms\Bundle\RedisSearchBundle\Service\SearchServiceInterface;

class MobileAppSettingSearchService implements MobileAppSettingSearchServiceInterface
{
    public function __construct(
        private readonly MobileAppSettingStorageInterface $storage,
        private readonly MobileAppSettingSearchServiceResultFactoryInterface $resultFactory,
        private readonly MobileAppSettingQueryBuilderFactoryInterface $queryBuilderFactory,
        private readonly SearchServiceInterface $searchService,
    ) {
    }

    public function findBy(
        string $operationSystem = '',
        string $name = '',
        int $limit = 0,
        int $offset = 0,
        ?bool $isEnabled = null,
    ): MobileAppSettingSearchServiceResultInterface {
        /** @var MobileAppSettingQueryBuilderInterface $searchQueryBuilder */
        $searchQueryBuilder = $this->queryBuilderFactory->create()
            ->setOffset($offset)
            ->setLimit($limit)
            ->setEnabled($isEnabled)
            ->setCalculateCount(true);

        if (false === empty($name)) {
            $searchQueryBuilder->setName($name);
        }

        if (false === empty($operationSystem)) {
            $searchQueryBuilder->setOperationSystem($operationSystem);
        }

        $result = $this->searchService->search($searchQueryBuilder->create());

        $idsToSearch = $result->getIds()?->toArray();

        return $this->resultFactory->create(
            \iterator_to_array($this->storage->getByIds($idsToSearch), false),
            $result->getCount(),
        );
    }
}
