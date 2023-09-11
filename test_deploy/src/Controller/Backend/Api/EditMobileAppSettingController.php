<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\Controller\Backend\Api;

use GinCms\Bundle\AdminBundle\Api\Response\ApiResponseFactoryInterface;
use GinCms\Bundle\CmsLoggerBundle\Log\Service\LogServiceInterface;
use GinCms\Bundle\MobileAppSettingsBundle\Form\MobileAppSettingType;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Enum\MobileAppSettingErrorEnum;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Mapper\MobileAppSettingDataMapperInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Service\MobileAppSettingServiceInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class EditMobileAppSettingController
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly ApiResponseFactoryInterface $apiResponseFactory,
        private readonly MobileAppSettingServiceInterface $mobileAppSettingService,
        private readonly LogServiceInterface $logService,
        private readonly MobileAppSettingDataMapperInterface $dataMapper,
    ) {
    }

    public function __invoke(Request $request, string $id): Response
    {
        if (null === $mobileAppSetting = $this->mobileAppSettingService->getById($id)) {
            return $this->apiResponseFactory->createError(
                MobileAppSettingErrorEnum::API_NOT_FOUND->value,
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        $form = $this->formFactory->create(MobileAppSettingType::class, $mobileAppSetting);
        $form->submit($request->request->all());

        if (false === $form->isValid()) {
            return $this->apiResponseFactory->createErrorFromForm($form);
        }

        /** @var MobileAppSettingInterface $newEntity */
        $newEntity = $form->getData();

        $result = $this->mobileAppSettingService->replace($mobileAppSetting, $newEntity);
        if (null !== $error = $result->getError()) {
            $this->logService->create(
                'mobile_app_setting:update_error',
                MobileAppSettingErrorEnum::API_FAILED_UPDATE->value,
                [
                    'code' => $error->getCode(),
                    'data' => $error->getData(),
                ]
            );

            return $this->apiResponseFactory->createError(
                MobileAppSettingErrorEnum::API_FAILED_UPDATE->value,
                JsonResponse::HTTP_BAD_REQUEST,
            );
        }

        return $this->apiResponseFactory->createSuccess(
            $this->dataMapper->toRaw($newEntity)
        );
    }
}
