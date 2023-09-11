<?php

declare(strict_types=1);

namespace Test\GinCms\Bundle\MobileAppSettingsBundle;

use GinCms\Bundle\TestBundle\Test\AbstractTest;

abstract class MobileAppSettingsAbstractTest extends AbstractTest
{
    protected function getTestEnv(): string
    {
        return 'mobile-app-setting';
    }
}
