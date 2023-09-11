<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\Controller\Backend\Api;

use GinCms\Bundle\AdminBundle\Api\Response\ApiResponseFactoryInterface;
use GinCms\Bundle\MobileAppSettingsBundle\Form\MobileAppSettingType;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Enum\MobileAppSettingErrorEnum;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Mapper\MobileAppSettingDataMapperInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Service\MobileAppSettingServiceInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CreateMobileAppSettingController
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly ApiResponseFactoryInterface $apiResponseFactory,
        private readonly MobileAppSettingServiceInterface $mobileAppSettingService,
        private readonly MobileAppSettingDataMapperInterface $dataMapper,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(type: MobileAppSettingType::class, options: ['is_new_setting' => true]);
        $form->submit($request->request->all());

        if (false === $form->isValid()) {
            return $this->apiResponseFactory->createErrorFromForm($form);
        }

        $appSetting = $form->getData();
        $result = $this->mobileAppSettingService->create($appSetting);

        if (null !== $error = $result->getError()) {
            return $this->apiResponseFactory->createError(
                MobileAppSettingErrorEnum::API_FAILED_TO_CREATE->value,
                data: $error->getData()
            );
        }

        return $this->apiResponseFactory->createSuccess(
            $this->dataMapper->toRaw($appSetting)
        );
    }
}
