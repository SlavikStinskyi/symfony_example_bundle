<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\Controller\Backend\Api;

use GinCms\Bundle\AdminBundle\Api\Response\ApiResponseFactoryInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Enum\MobileAppSettingErrorEnum;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Mapper\MobileAppSettingDataMapperInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Service\MobileAppSettingServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GetMobileAppSettingController
{
    public function __construct(
        private readonly MobileAppSettingServiceInterface $mobileAppSettingService,
        private readonly ApiResponseFactoryInterface $apiResponseFactory,
        private readonly MobileAppSettingDataMapperInterface $dataMapper,
    ) {
    }

    public function __invoke(string $id): Response
    {
        if (null === $setting = $this->mobileAppSettingService->getById($id)) {
            return $this->apiResponseFactory->createError(
                MobileAppSettingErrorEnum::API_NOT_FOUND->value,
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        return $this->apiResponseFactory->createSuccess(
            $this->dataMapper->toRaw($setting)
        );
    }
}
