<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\Form;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Factory\MobileAppSettingFactoryInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;
use Symfony\Component\Form\DataMapperInterface;

class MobileAppSettingCopyType extends MobileAppSettingType implements DataMapperInterface
{
    public function __construct(
        MobileAppSettingFactoryInterface $factory,
    ) {
        parent::__construct($factory);
    }

    public function mapDataToForms($viewData, $forms): void
    {
        /** @var MobileAppSettingInterface|null $viewData */
        $formsMap = \iterator_to_array($forms);

        $formsMap[MobileAppSettingInterface::FIELD_NAME]->setData($viewData ? 'copy_of_' . $viewData->getName() : '');
        $formsMap[MobileAppSettingInterface::FIELD_DEEPLINK]->setData($viewData ? $viewData->getDeeplink() : '');
        $formsMap[MobileAppSettingInterface::FIELD_OPERATION_SYSTEM]->setData($viewData ? $viewData->getOperationSystem() : null);
        $formsMap[MobileAppSettingInterface::FIELD_RATE]->setData($viewData ? $viewData->getAppRate() : 0);
        $formsMap[MobileAppSettingInterface::FIELD_BANNED_REF_CODES]->setData($viewData ? $viewData->getBannedRefCodes() : []);
        $formsMap[MobileAppSettingInterface::FIELD_IS_ENABLED]->setData(false);
    }
}
