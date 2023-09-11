<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Schema;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;
use GinCms\Bundle\RedisSearchBundle\Index\Document\IndexDocumentInterface;
use GinCms\Bundle\RedisSearchBundle\Index\Schema\IndexSchemaInterface;

interface MobileAppSettingIndexSchemaInterface extends IndexSchemaInterface
{
    public const FIELD_NAME = 'name';

    public const FIELD_OPERATION_SYSTEM = 'operation_system';

    public const FIELD_BANNED_REF_CODES = 'banned_ref_codes';

    public const FIELD_IS_ENABLED = 'is_enabled';

    public const FIELD_RATE = 'app_rate';

    public function getFieldName(): string;

    public function getFieldOperationSystem(): string;

    public function getFieldBannedRefCodes(): string;

    public function getFieldIsEnabled(): string;

    public function getFieldRate(): string;

    public function extractDocument(MobileAppSettingInterface $mobileAppSetting): IndexDocumentInterface;
}
