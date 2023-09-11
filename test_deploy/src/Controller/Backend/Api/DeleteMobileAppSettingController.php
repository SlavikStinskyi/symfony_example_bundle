<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\Controller\Backend\Api;

use GinCms\Bundle\AdminBundle\Api\Response\ApiResponseFactoryInterface;
use GinCms\Bundle\CmsLoggerBundle\Log\Service\LogServiceInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Enum\MobileAppSettingErrorEnum;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Service\MobileAppSettingServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DeleteMobileAppSettingController
{
    public function __construct(
        private readonly ApiResponseFactoryInterface $apiResponseFactory,
        private readonly MobileAppSettingServiceInterface $mobileAppSettingService,
        private readonly LogServiceInterface $logService,
    ) {
    }

    public function __invoke(string $id): Response
    {
        if (null === $mobileAppSetting = $this->mobileAppSettingService->getById($id)) {
            return $this->apiResponseFactory->createError(
                MobileAppSettingErrorEnum::API_NOT_FOUND->value,
                JsonResponse::HTTP_NOT_FOUND,
                [
                    'id' => $id,
                ]
            );
        }

        $result = $this->mobileAppSettingService->remove($mobileAppSetting);

        if (null !== $error = $result->getError()) {
            $this->logService->create(
                'mobile_app_setting:remove_error',
                MobileAppSettingErrorEnum::API_FAILED_REMOVE->value,
                [
                    'code' => $error->getCode(),
                    'data' => $error->getData(),
                ]
            );

            return $this->apiResponseFactory->createError(
                MobileAppSettingErrorEnum::API_FAILED_REMOVE->value,
                JsonResponse::HTTP_BAD_REQUEST,
                [
                    'id' => $id,
                ]
            );
        }

        return $this->apiResponseFactory->createSuccess();
    }
}
