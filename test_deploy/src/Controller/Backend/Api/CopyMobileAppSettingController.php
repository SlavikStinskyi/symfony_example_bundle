<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\Controller\Backend\Api;

use GinCms\Bundle\AdminBundle\Api\Response\ApiResponseFactoryInterface;
use GinCms\Bundle\MobileAppSettingsBundle\Form\MobileAppSettingCopyType;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Service\MobileAppSettingServiceInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Enum\MobileAppSettingErrorEnum;

class CopyMobileAppSettingController
{
    public function __construct(
        private readonly ApiResponseFactoryInterface $apiResponseFactory,
        private readonly MobileAppSettingServiceInterface $mobileAppSettingService,
    ) {
    }

    public function __invoke(string $id): Response
    {
        if (null === $mobileAppSetting = $this->mobileAppSettingService->getById($id)) {
            return $this->apiResponseFactory->createError(
                MobileAppSettingErrorEnum::API_FAILED_TO_CREATE,
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        $result = $this->mobileAppSettingService->copy($mobileAppSetting);

        if (null !== $error = $result->getError()) {
            return $this->apiResponseFactory->createError(
                MobileAppSettingErrorEnum::API_FAILED_TO_CREATE,
                data: $error->getData()
            );
        }

        return $this->apiResponseFactory->createSuccess();
    }
}
