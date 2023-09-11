<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Enum;

enum MobileAppSettingErrorEnum: string
{
    case API_FAILED_TO_CREATE = 'Failed to create mobile application settings';
    case API_NOT_FOUND = 'Mobile application setting is not found';
    case API_FAILED_REMOVE = 'Failed to remove application setting';
    case API_FAILED_UPDATE = 'Failed to update application setting';
}
