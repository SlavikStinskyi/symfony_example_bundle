<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\Graphql\Frontend\Controller;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Enum\OperationSystemEnum;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Service\MobileAppSettingSearchServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use TheCodingMachine\GraphQLite\Annotations\Query;

class GetMobileSettingsController
{
    public function __construct(
        private readonly MobileAppSettingSearchServiceInterface $searchService,
    ) {
    }

    /**
     * @param Request $request
     * @param OperationSystemEnum|string $operation_system
     * @param bool $is_enabled
     * 
     * @return MobileAppSettingInterface[]
     */
    #[Query(name: 'mobileAppSettings')]
    public function __invoke(
        Request $request,
        OperationSystemEnum | string $operation_system = '',
        bool $is_enabled = true,
    ) {
        $settingsResult = $this->searchService->findBy(
            $operation_system,
            '',
            0,
            0,
            $is_enabled
        );

        return \array_map(
            fn (MobileAppSettingInterface $setting) => $this->dataMapper->toRaw($setting),
            $settingsResult->getMobileAppSettings()
        );
    }
}
