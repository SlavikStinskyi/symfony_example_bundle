<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\Controller\Backend\Api;

use GinCms\Bundle\AdminBundle\Api\Response\ApiResponseFactoryInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Mapper\MobileAppSettingDataMapperInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Service\MobileAppSettingSearchServiceInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Service\Result\MobileAppSettingSearchServiceResultInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetListMobileAppSettingController
{
    private const COUNT = 'count';
    private const MOBILE_APP_SETTINGS = 'mobile_app_settings';

    public function __construct(
        private readonly ApiResponseFactoryInterface $apiResponseFactory,
        private readonly MobileAppSettingDataMapperInterface $dataMapper,
        private readonly MobileAppSettingSearchServiceInterface $searchService,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $limit = (int) $request->request->get('limit', 15);
        $offset = (int) $request->request->get('offset', 0);
        $name = (string) $request->request->get('name', '');
        $operationSystem = (string) $request->request->get('operation_system', '');
        $isEnabled = null === $request->request->get('is_enabled')
            ? null
            : (bool) $request->request->get('is_enabled');

        $settingsResult = $this->searchService->findBy(
            $operationSystem,
            $name,
            $limit,
            $offset,
            $isEnabled
        );

        return $this->apiResponseFactory->createSuccess(
            [
                MobileAppSettingSearchServiceResultInterface::FIELD_COUNT => $settingsResult->getCount(),
                MobileAppSettingSearchServiceResultInterface::FIELD_MOBILE_APP_SETTINGS => \array_map(
                    fn (MobileAppSettingInterface $setting) => $this->dataMapper->toRaw($setting),
                    $settingsResult->getMobileAppSettings()
                ),
            ]
        );
    }
}
