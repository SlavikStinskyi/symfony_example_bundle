<?php

declare(strict_types=1);

namespace GinCms\Bundle\BannerBundle\Graphql\Type;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;
use TheCodingMachine\GraphQLite\Annotations\SourceField;
use TheCodingMachine\GraphQLite\Annotations\Type;

#[Type(class: MobileAppSettingInterface::class)]
#[SourceField(name: 'name')]
#[SourceField(name: 'operation_system')]
#[SourceField(name: 'deeplink')]
#[SourceField(name: 'app_rate')]
#[SourceField(name: 'banned_ref_codes')]
#[SourceField(name: 'is_enabled')]
class MobileSettingsType
{
}