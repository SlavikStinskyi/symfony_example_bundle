<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Generator;

use GameInspire\IdGenerator\IdGeneratorInterface;

/**
 * @internal
 */
interface MobileAppSettingIdGeneratorInterface extends IdGeneratorInterface
{
    public function generateEntityId();
}
