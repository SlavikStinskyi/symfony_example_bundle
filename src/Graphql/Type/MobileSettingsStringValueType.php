<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\Graphql\Type;

use GinCms\Bundle\RouteDisplaySettingsBundle\RouteDisplaySettings\Value\RouteDisplaySettingsStringValue;
use TheCodingMachine\GraphQLite\Annotations\SourceField;
use TheCodingMachine\GraphQLite\Annotations\Type;

#[Type(class: RouteDisplaySettingsStringValue::class)]
#[SourceField(name: 'value')]
class MobileSettingsStringValueType
{
}
